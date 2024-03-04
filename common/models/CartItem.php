<?php

namespace common\models;

use common\entities\ProductOptions;
use common\entities\Products;
use common\entities\ProductWeights;

class CartItem
{
    private $product;
    public $quantity;
    public $weight;
    public $options;

    public function __construct(Products $product, $quantity = 1, $weight, $options)
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->weight = $weight;
        $this->options = $options;
    }

    public function getId()
    {
        return md5(serialize([$this->product->id]));
    }

    public function getProductId()
    {
        return $this->product->id;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        if ($this->weight) {
            return $this->product->getProductBasePriceWithWeight($this->weight);
        }
        return $this->product->getProductBasePrice();
    }

    public function getPriceWithOptions()
    {
        $price = $this->getPrice();
        foreach ($this->getOptions() as $option) {
            $price += $option->getPrice();
        }
        return $price;
    }

    public function getCost()
    {
        return $this->getPriceWithOptions() * $this->quantity;
    }

    public function getWeight()
    {
        return ProductWeights::findOne(['id' => $this->weight, 'product_id' => $this->getProductId()]);
    }

    public function getWeightTitle()
    {
        if ($this->getWeight()) {
            return $this->getWeight()->title;
        }
        return null;
    }

    public function getOptionsIds()
    {
        if (strlen($this->options) > 0) {
            return explode('__',$this->options);
        }
        return array();
    }

    public function getOptionsAsStr()
    {
        if (strlen($this->options) > 0) {
            return $this->options;
        }
        return null;
    }

    public function getOptions()
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

    public function plus($quantity)
    {
        return new static($this->product, $this->quantity + $quantity, $this->weight, $this->options);
    }

    public function changeQuantity($quantity)
    {
        return new static($this->product, $quantity, $this->weight, $this->options);
    }

    public function changeOptions($options)
    {
        return new static($this->product, $this->quantity, $this->weight, $options);
    }
}