<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_categories}}`.
 */
class m220810_122633_create_menu_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_categories}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'slug' => $this->string(),
            'sort' => $this->integer(),
            'status' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_categories}}');
    }
}
