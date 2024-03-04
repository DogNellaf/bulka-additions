<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_products}}`.
 */
class m220810_123303_create_menu_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_products}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'price' => $this->integer(),
            'description' => $this->text(),
            'title_desc' => $this->string(),
            'additional' => $this->string(),
            'slug' => $this->string(),
            'link' => $this->string(),
            'href' => $this->string(),
            'status' => $this->boolean()->defaultValue(0),
            'image_name' => $this->string(),
            'sort' => $this->integer(),
            'category_id' => $this->integer(),
            'title_icon' => $this->integer(),
            'desc_icon' => $this->integer(),
            'link_icon' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'product_to_category',
            'blk_menu_products',
            'category_id',
            'blk_menu_categories',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'title_icon',
            'blk_menu_products',
            'title_icon',
            'blk_menu_icons',
            'id',
            'CASCADE'
        );


        $this->addForeignKey(
            'desc_icon',
            'blk_menu_products',
            'desc_icon',
            'blk_menu_icons',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'link_icon',
            'blk_menu_products',
            'link_icon',
            'blk_menu_icons',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_products}}');
    }
}
