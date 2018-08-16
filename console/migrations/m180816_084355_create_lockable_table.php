<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lockable`.
 */
class m180816_084355_create_lockable_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lockable}}', [
            'id'         => $this->primaryKey(),
            'model_name' => $this->string(55)->notNull(),
            'model_id'   => $this->integer(11)->notNull(),
            'user_id'    => $this->integer(11)->notNull(),
            'unlock_at'  => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%lockable}}');
    }
}
