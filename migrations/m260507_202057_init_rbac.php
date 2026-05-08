<?php

use yii\db\Migration;

class m260507_202057_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $file = Yii::getAlias('@yii/rbac/migrations/m140506_102106_rbac_init.php');

        if (file_exists($file)) {
            require_once($file);

            $baseMigration = new \m140506_102106_rbac_init();
            $baseMigration->db = $this->db;
            $baseMigration->up();
        }

        $auth = Yii::$app->authManager;

        $auth->add($auth->createRole('customer'));
        $auth->add($auth->createRole('contractor'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $file = Yii::getAlias('@yii/rbac/migrations/m140506_102106_rbac_init.php');

        if (file_exists($file)) {
            require_once($file);

            $baseMigration = new \m140506_102106_rbac_init();
            $baseMigration->db = $this->db;
            $baseMigration->down();
        }
    }
}
