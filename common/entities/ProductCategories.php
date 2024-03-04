<?php

namespace common\entities;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%product_categories}}".
 *
 * @property int $id
 * @property int $target
 * @property string $title
 * @property string $slug
 * @property int $sort
 * @property int $status
 *
 * @property ProductSections $target0
 * @property Products[] $products
 */
class ProductCategories extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_categories}}';
    }

    public function behaviors( ) {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['target', 'title'], 'required'],
            [['target', 'sort', 'status'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 50],
            [['target'], 'exist', 'skipOnError' => true, 'targetClass' => ProductSections::className(), 'targetAttribute' => ['target' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target' => 'Раздел',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getTarget0()
    {
        return $this->hasOne(ProductSections::className(), ['id' => 'target']);
    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['category_id' => 'id']);
    }
}
