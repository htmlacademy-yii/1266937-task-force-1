<?php

use yii\db\Migration;

class m260508_051607_add_is_contacts_public_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'is_contacts_public', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'is_contacts_public');
    }
}
