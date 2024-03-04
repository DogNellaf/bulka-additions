<?php

namespace common\models\storage;

use common\models\CartItem;

interface StorageInterface
{
    /**
     * @return CartItem[]
     */
    public function load();

    /**
     * @param CartItem[] $items
     * @return void
     */
    public function save(array $items);
}