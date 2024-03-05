<?php

namespace common\models;

use common\entities\Modules;
use common\entities\Products;
use common\entities\ProductWeights;
use common\models\storage\StorageInterface;
use Yii;
use yii\helpers\Json;

class Cart
{
    private $storage;
    private $items;
    private $bonuses;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function setBonuses($bonuses)
    {
        $this->bonuses = $bonuses;
        $this->saveBonuses();
    }

    public function getBonuses()
    {
        return $this->bonuses;
    }

    public function getItems()
    {
        $this->loadItems();
        return $this->items;
    }

    public function getItem($id, $weight = null, $options = null)
    {
        /* @var $item CartItem */
        $this->loadItems();
        foreach ($this->items as $item) {
            if ($item === false){
                continue;
            }
            if ($item->getProductId() == $id && $item->weight == $weight && $item->options == $options) {
                return $item;
            }
        }
        return false;
    }

    public function getAmount()
    {
        $this->loadItems();
        return count($this->items);
    }

    public function getTotalAmount()
    {
        /* @var $item CartItem */
        $this->loadItems();
        $count = 0;
        foreach ($this->items as $item) {
            if ($item === false){
                continue;
            }
            $count += (int) $item->getQuantity();
        }
        return $count;
    }

    public function add(CartItem $item)
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProductId() == $item->getProductId() && $current->weight == $item->weight && $current->options == $item->options) {
                $this->items[$i] = $current->plus($item->getQuantity());
                if ($this->items[$i]->quantity < 1) {
                    unset($this->items[$i]);
                }
                $this->saveItems();
                return;
            }
        }
        $this->items[] = $item;
        $this->saveItems();
    }

    public function set($id, $quantity, $weight = null, $options = '')
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current === false){
                continue;
            }
            if ($current->getProductId() == $id && $current->weight == $weight && $current->options == $options) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Блюдо не найдено.');
    }

    public function setOptions($id, $weight = null, $options = '', $newOptions = '')
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current === false){
                continue;
            }
            if ($current->getProductId() == $id && $current->weight == $weight && $current->options == $options) {
                $this->items[$i] = $current->changeOptions($newOptions);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Блюдо не найдено.');
    }

    public function remove($id, $weight = null, $options = null)
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current === false){
                continue;
            }
            if ($current->getProductId() == $id && $current->weight == $weight && $current->options == $options) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Блюдо не найдено.');
    }

    public function clear()
    {
        $this->items = [];
        $this->saveItems();
    }

    public function getCost()
    {
        /* @var  $item CartItem */
        $this->loadItems();
        $cost = 0;
        foreach ($this->items as $item) {
            if ($item === false){
                continue;
            }
            $cost += $item->getCost();
        }
        return $cost - $this->$bonuses;
    }

    public function getTotalCost()
    {
        $cost = $this->getCost();
        /* @var $cost_module \common\entities\Modules */
        $cost_module = Modules::findOne(9);
        if ($cost < $cost_module->min_order_sum - $this->$bonuses) {
            return false;
        }
        return $cost;
    }

    public function isAllowedCost()
    {
        /* @var $user \common\entities\User */
        $user = Yii::$app->user->identity;
        if (!Yii::$app->user->isGuest && $user->isBusinessWholesale()) {
            return true;
        }
        $cost = $this->getCost();
        /* @var $cost_module \common\entities\Modules */
        $cost_module = Modules::findOne(9);
        if ($cost < $cost_module->min_order_sum - $this->$bonuses) {
            return false;
        }
        return true;
    }

    public function getNotEnoughQtyItems()
    {
        $disallowedItems = [];
        /* @var  $item \common\models\CartItem */
        foreach ($this->getItems() as $item) {
            if ($item === false){
                continue;
            }
            /* @var  $weight ProductWeights */
            $weight = $item->getWeight();
            if ($item->quantity < $weight->min_quantity) {
                $disallowedItems[] = [
                    'title' => $item->getProduct()->title,
                    'weight' => $item->getWeightTitle(),
                    'min_qty' => $weight->min_quantity,
                ];
            }
        }
        return $disallowedItems;
    }

    public function isEnoughItemsQty()
    {
        if ($this->getNotEnoughQtyItems()) {
            return false;
        }
        return true;
    }

    public function getMinDeliveryDays()
    {
        /* @var  $item CartItem */
        $this->loadItems();
        $days = 0;
        foreach ($this->items as $item) {
            if ($item === false){
                continue;
            }
            if ($item->getProduct()->min_delivery_days > $days) {
                $days = $item->getProduct()->min_delivery_days;
            }
        }
        return $days;
    }

    /*
    public function getWeight()
    {
        $this->loadItems();
        return array_sum(array_map(function (CartItem $item) {
            return $item->getWeight();
        }, $this->items));
    }
    */

    public function setArrayJson(Cart $cart)
    {
        /* @var $item CartItem */
        /* @var $product Products */
        $array = [];
        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            array_push($array, [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'description' => $product->description,
                //'weight' => $product->weight,
                'image' => $product->image,
                'quantity' => $item->getQuantity(),
                'cost' => $item->getCost(),
                'weight' => $item->weight,
                'options' => $item->options,
            ]);
        }
        $array_json = Json::encode($array);
        return $array_json;
    }

    private function loadItems()
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems()
    {
        $this->storage->save($this->items);
    }

    private function saveBonuses()
    {
        $this->storage->save($this->bonuses);
    }
} 
