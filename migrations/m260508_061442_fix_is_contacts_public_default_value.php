<?php

use yii\db\Migration;

class m260508_061442_fix_is_contacts_public_default_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('users', ['is_contacts_public' => 0]);

        $this->alterColumn('users', 'is_contacts_public', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('users', 'is_contacts_public', $this->boolean()->defaultValue(1));
        $this->update('users', ['is_contacts_public' => 1]);
    }
}
