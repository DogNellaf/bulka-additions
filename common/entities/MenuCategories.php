<?php

namespace common\entities;

use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu_categories}}".
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $slug
 * @property int $status


 */
class MenuCategories extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%menu_categories}}';
    }

    public function behaviors( ) {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title_ru',
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
            [['title_ru', 'title_en'], 'required'],
            [['sort', 'status'], 'integer'],
            [['slug', 'title_ru', 'title_ru'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок RU',
            'title_en' => 'Заголовок EN',
            'status' => 'Статус',
        ];
    }


    public function getProducts()
    {
        return $this->hasMany(MenuProducts::class, ['category_id' => 'id'])->
        orderBy(['sort' => SORT_ASC]);
    }
}
