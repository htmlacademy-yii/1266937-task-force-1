<?php

use yii\db\Migration;

class m260428_213716_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $auth->add($auth->createRole('customer'));
        $auth->add($auth->createRole('contractor'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260428_213716_init_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260428_213716_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
