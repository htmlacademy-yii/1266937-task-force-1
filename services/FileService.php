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
}