<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%product_options}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property int $price
 * @property int $business_price
 * @property int $sort
 * @property int $status
 *
 * @property Products $product
 */
class ProductOptions extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_options}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return ProductOptions::find()->where(['product_id' => $this->product_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['product_id', 'title', 'price', 'business_price'], 'required'],
            [['product_id', 'price', 'business_price', 'sort', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'title' => 'Заголовок',
            'price' => 'Цена',
            'business_price' => 'Бизнес-цена',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

    public function getPrice()
    {
        /* @var $user \common\entities\User */
        $user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest && $user->isBusinessWholesale()) {
            return $this->business_price;
        }
        return $this->price;
    }
}
