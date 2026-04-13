<?php

require_once 'vendor/autoload.php';

use Taskforce\Utils\CsvToSqlConverter;
use Taskforce\Exceptions\FileFormatException;
use Taskforce\Exceptions\SourceFileException;

function importCsv(string $csvPath, string $tableName, string $outputPath): void
{
    try {
        $converter = new CsvToSqlConverter($csvPath, $tableName);
        $converter->convertAndSave($outputPath);

        echo "SQL файл для таблицы '{$tableName}' успешно создан: {$outputPath}\n";
    } catch (FileFormatException | SourceFileException $e) {
        error_log("Ошибка обработки файла: '{$csvPath}': " . $e->getMessage());
        echo "Не удалось обработать файл: '{$csvPath}'\n";
    }
}

importCsv('data/categories.csv', 'categories', 'sql/categories.sql');
importCsv('data/cities.csv', 'cities', 'sql/cities.sql');

echo "Обработка завершена! \n";
