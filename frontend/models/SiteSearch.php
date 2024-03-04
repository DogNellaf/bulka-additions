<?php

namespace frontend\models;

use common\entities\Products;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SiteSearch extends Model
{
    public $str;

    public function __construct($str, array $config = [])
    {
        $this->str = $str;
        parent::__construct($config);
    }

    public function search()
    {
        if ($this->str) {
            $products = Products::find()->having(['status' => 1])->andWhere([
                'or',
                ['like', 'title', $this->str],
                ['like', 'description', $this->str]
            ])->groupBy('id');

            $dataProvider = new ActiveDataProvider([
                'query' => $products,
                'sort' => ['defaultOrder' => ['sort' => SORT_ASC]],
                'pagination' => false
            ]);

            return $dataProvider;
        }
        return false;
    }
}