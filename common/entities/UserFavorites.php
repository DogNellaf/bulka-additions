<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_favorites}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 *
 * @property Products $product
 */
class UserFavorites extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_favorites}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }
}
