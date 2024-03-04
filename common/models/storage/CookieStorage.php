<?php

namespace common\models\storage;

use common\entities\Products;
use common\models\CartItem;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

class CookieStorage implements StorageInterface
{
    private $key;
    private $timeout;

    public function __construct($key, $timeout)
    {
        $this->key = $key;
        $this->timeout = $timeout;
    }

    public function load()
    {
        if ($cookie = Yii::$app->request->cookies->get($this->key)) {
            return array_filter(array_map(function (array $row) {
                if (isset($row['p'], $row['q']) && $product = Products::find()->andWhere(['id' => $row['p']])->one()) {
                    /** @var Products $product */
                    return new CartItem($product, $row['q'], $row['w'], $row['o']);
                }
                return false;
            }, Json::decode($cookie->value)));
        }
        return [];
    }

    public function save(array $items)
    {
        Yii::$app->response->cookies->add(new Cookie([
            'name' => $this->key,
            'value' => Json::encode(array_map(function (CartItem $item) {
                return [
                    'p' => $item->getProductId(),
                    'q' => $item->getQuantity(),
                    'w' => $item->weight,
                    'o' => $item->options,
                ];
            }, $items)),
            'expire' => time() + $this->timeout,
        ]));
    }
} 