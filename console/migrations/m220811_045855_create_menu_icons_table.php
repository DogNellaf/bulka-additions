<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_icons}}`.
 */
class m220811_045855_create_menu_icons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_icons}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'image_name' => $this->string(),
            'slug' => $this->string(),
            'sort' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_icons}}');
    }
}
