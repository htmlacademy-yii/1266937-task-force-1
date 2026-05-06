<?php

use yii\db\Migration;

class m260506_050054_add_columns_to_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('files', 'name', $this->string()->notNull());
        $this->addColumn('files', 'size', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260506_050054_add_columns_to_file cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260506_050054_add_columns_to_file cannot be reverted.\n";

        return false;
    }
    */
}
