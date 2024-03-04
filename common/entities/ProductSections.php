<?php

namespace common\entities;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%product_sections}}".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $sort
 * @property int $status
 *
 * @property ProductCategories[] $productCategories
 */
class ProductSections extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_sections}}';
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
            [['title'], 'required'],
            [['sort', 'status'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getProductCategories()
    {
        return $this->hasMany(ProductCategories::className(), ['target' => 'id'])->having(['status' => 1])->orderBy(['sort' => SORT_ASC]);
    }
}
