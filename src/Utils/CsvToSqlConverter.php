<?php

namespace Taskforce\Utils;

use SplFileObject;
use Taskforce\Exceptions\SourceFileException;
use Taskforce\Exceptions\FileFormatException;

class CsvToSqlConverter
{
    private string $fileName;
    private string $tableName;
    private array $headers = [];

    public function __construct($fileName, $tableName)
    {
        if (!file_exists($fileName)) {
            throw new SourceFileException("Исходный файл не существует: '{$fileName}'");
        }

        $this->fileName = $fileName;
        $this->tableName = $tableName;

        $this->readHeaders();
    }

    private function readHeaders(): void
    {
        $file = new SplFileObject($this->fileName, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);

        $headers = $file->fgetcsv(',', '"', '\\');

        if (empty($headers)) {
            throw new FileFormatException("Файл '{$this->fileName}' пустой или имеет некорректный формат");
        }

        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);
        $headers = array_map('trim', $headers);

        $this->headers = $headers;
    }

    public function convertAndSave(string $outputFile): void
    {
        $csvFile = new SplFileObject($this->fileName, 'r');
        $csvFile->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);

        $sqlFile = new SplFileObject($outputFile, 'w');

        $quotedColumns = array_map(fn($column) => "`$column`", $this->headers);
        $columnsString = implode(', ', $quotedColumns);

        foreach ($csvFile as $index => $data) {

            if ($index === 0) {
                continue;
            }

            if (isset($data) && \count($data) === \count($this->headers)) {

                $values = array_map(function ($value) {
                    if ($value === null || $value === '') {
                        return "NULL";
                    }
                    $escaped = str_replace("'", "''", $value);
                    return "'{$escaped}'";
                }, $data);

                $valuesString = implode(', ', $values);

                $sqlQuery = "INSERT INTO `{$this->tableName}` ({$columnsString}) VALUES ({$valuesString});\n";

                $sqlFile->fwrite($sqlQuery);
            }
        }
    }
}
