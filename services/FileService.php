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
    $folder = ltrim($folder, '/');
    $folderPath = Yii::getAlias("@webroot/{$folder}");

    try {
      if (!is_dir($folderPath)) {
        FileHelper::createDirectory($folderPath, 0775);
      }

      $fileHash = Yii::$app->security->generateRandomString(12);
      $newName = "{$fileHash}.{$file->getExtension()}";

      $fullPath = "{$folderPath}/{$newName}";

      $fileRecord = new File();
      $fileRecord->url = "/{$folder}/{$newName}";
      $fileRecord->file_path = "{$folder}/{$newName}";
      $fileRecord->name = $file->name;
      $fileRecord->size = $file->size;

      if ($fileRecord->save()) {

        if ($file->saveAs($fullPath)) {
          return $fileRecord;
        }

        $fileRecord->delete();
      }

    } catch (\Throwable $e) {
      Yii::error("Ошибка в FileService::saveFile: " . $e->getMessage());
    }

    return null;
  }
}