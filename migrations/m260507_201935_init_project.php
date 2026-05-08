<?php

use yii\db\Migration;

class m260507_201935_init_project extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sqlPath = Yii::getAlias('@app/sql');

        $this->runSqlFile($sqlPath . '/schema.sql');
        $this->runSqlFile($sqlPath . '/seed/categories.sql');
        $this->runSqlFile($sqlPath . '/seed/cities.sql');
    }

    /**
     * Проверяет файл и выполняет SQL-запрос
     * 
     * @param string $path Путь к SQL-файлу
     * @throws \yii\base\Exception Если файл не найден
     * 
     * @return void
     */
    private function runSqlFile(string $path): void
    {
        if (!file_exists($path)) {
            throw new \yii\base\Exception("SQL файл не найден: $path");
        }

        $sql = file_get_contents($path);
        if ($sql) {
            $this->execute($sql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reviews');
        $this->dropTable('responses');
        $this->dropTable('task_files');
        $this->dropTable('user_categories');
        $this->dropTable('tasks');
        $this->dropTable('users');
        $this->dropTable('files');
        $this->dropTable('cities');
        $this->dropTable('categories');
    }
}
