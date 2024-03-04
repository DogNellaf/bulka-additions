<?php

namespace common\models\storage;

use common\models\CartItem;
use common\entities\Products;
use yii\db\Connection;
use yii\db\Query;

class DbStorage implements StorageInterface
{
    private $userId;
    private $db;

    public function __construct($userId, Connection $db)
    {
        $this->userId = $userId;
        $this->db = $db;
    }

    public function load()
    {
        $rows = (new Query())
            ->select('*')
            ->from('{{%cart}}')
            ->where(['user_id' => $this->userId])
            ->orderBy(['product_id' => SORT_ASC])
            ->all($this->db);

        return array_map(function (array $row) {
            /** @var Products $product */
            if ($product = Products::find()->andWhere(['id' => $row['product_id'], 'status' => 1])->one()) {
                return new CartItem($product, $row['quantity'], $row['weight'], $row['options']);
            }
            return false;
        }, $rows);
    }

    public function save(array $items)
    {
        $this->db->createCommand()->delete('{{%cart}}', [
            'user_id' => $this->userId,
        ])->execute();

        $this->db->createCommand()->batchInsert(
            '{{%cart}}',
            [
                'user_id',
                'product_id',
                'quantity',
                'weight',
                'options'
            ],
            array_map(function (CartItem $item) {
                return [
                    'user_id' => $this->userId,
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'weight' => $item->weight,
                    'options' => $item->options,
                ];
            }, $items)
        )->execute();
    }
} 