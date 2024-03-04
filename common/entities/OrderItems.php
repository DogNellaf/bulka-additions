<?php

namespace common\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $title
 * @property int $weight
 * @property string $options
 * @property int $qty_item
 * @property float $price_item
 *
 * @property Orders $order
 * @property Products $product
 */
class OrderItems extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'product_id', 'qty_item', 'title'], 'safe'],
            [['order_id', 'product_id', 'qty_item'], 'integer'],
            [['price_item'], 'number'],
            [['title', 'options'], 'string', 'max' => 255],
            [['weight'], 'integer'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Orders::class, ['id' => 'order_id']);
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    public function getItemOptions()
    {
        if (strlen($this->options) > 0) {
            $optionsIds = explode('__',$this->options);
            /** @var ProductOptions[]|array $options */
            if ($options = ProductOptions::find()->andWhere(['id' => $optionsIds])->all()) {
                return $options;
            }
        }
        return array();
    }

    public function getWeight()
    {
        return ProductWeights::findOne(['id' => $this->weight, 'product_id' => $this->product_id]);
    }

}
