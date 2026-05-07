<?php

namespace app\services;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use app\models\File;

class FileService
{
  /**
   * Сохраняет файл на диск и создает запись в таблице файлов
   * @param yii\web\UploadedFile $file Входной объект загруженного файла
   * @param string $folder Подпапка внутри директории web (по умолчанию 'uploads')
   * 
   * @return File|null Объект модели File или null при ошибке
   * 
   * @throws \Throwable
   */
  public static function saveFile(UploadedFile $file, string $folder = 'uploads'): ?File
  {
    $folderPath = Yii::getAlias("@webroot/{$folder}");

    try {
      $fileHash = Yii::$app->security->generateRandomString(12);
      $fileExtention = $file->getExtension();
      $newName = "{$fileHash}.{$fileExtention}";

      $fullPath = "{$folderPath}/{$newName}";

      $fileRecord = new File();
      $fileRecord->url = "/{$folder}/{$newName}";
      $fileRecord->file_path = "{$folder}/{$newName}";
      $fileRecord->name = $file->name;
      $fileRecord->size = $file->size;

      if ($fileRecord->save()) {

        if (!is_dir($folderPath)) {
          FileHelper::createDirectory($folderPath, 0775);
        }

        if ($file->saveAs($fullPath)) {
          return $fileRecord;
        }

        $fileRecord->delete();
      }

      return null;
    } catch (\Throwable $e) {
      Yii::error("Ошибка в FileService::saveFile: " . $e->getMessage());
    }

    return null;
  }

  /**
   * Удаляет файл с диска и запись из таблицы файлов
   * @param int|null $fileId ID файла в таблице files
   * 
   * @return bool Возвращает true при успешном удалении записи, false при ошибке удаления или если файл не найден
   */
  public static function deleteFile(?int $fileId): bool
  {
    if (!$fileId) {
      return false;
    }

    $fileRecord = File::findOne($fileId);
    if (!$fileRecord) {
      return false;
    }

    $fullPath = Yii::getAlias('@webroot') . $fileRecord->file_path;

    if (file_exists($fullPath)) {
      if (!is_writable($fullPath) || !unlink($fullPath)) {
        Yii::error("Не удалось удалить файл с диска: {$fullPath}");

        return false;
      }
    }

    if (!$fileRecord->delete()) {
      Yii::error("Не удалось удалить запись из таблицы для файла с ID: {$fileId}");

      return false;
    }

    return true;
  }
}