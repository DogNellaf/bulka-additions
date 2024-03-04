<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chef}}`.
 */
class m220824_103458_create_chef_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chef}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'slug' => $this->string(),
            'link' => $this->string(),
            'href' => $this->string(),
            'description' => $this->text(),
            'image_name' => $this->string(),
            'status' => $this->boolean()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'sort' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chef}}');
    }
}
