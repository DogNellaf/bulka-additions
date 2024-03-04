<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\Orders;

/**
 * OrdersSearch represents the model behind the search form of `common\forms\Orders`.
 */
class OrdersSearch extends \common\entities\Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name', 'email','phone', 'datetime', 'pay_method'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'status' => $this->status,
            'user_status' => $this->user_status,
            'pay_method' => $this->pay_method,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['>=', 'datetime', $this->datetime ? strtotime($this->datetime . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'datetime', $this->datetime ? strtotime($this->datetime . ' 23:59:59') : null])
         ;

        return $dataProvider;
    }
}
