<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%product_weights}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property number $price
 * @property number $business_price
 * @property int $min_quantity
 * @property int $sort
 * @property int $status
 * @property int $balance
 * @property int $id_1c
 *
 * @property Products $product
 */
class ProductWeights extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_weights}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return ProductWeights::find()->where(['product_id' => $this->product_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['product_id', 'title', 'price', 'business_price', 'id_1c'], 'required'],
            [['product_id', 'min_quantity', 'sort', 'status', 'balance'], 'integer'],
            [['price', 'business_price'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['id_1c'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Продукт',
            'title' => 'Заголовок',
            'price' => 'Цена',
            'business_price' => 'Бизнес-цена',
            'min_quantity' => 'Минимальное количество в заказе',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'balance' => 'Остатки',
            'id_1c' => '1С ID',
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
