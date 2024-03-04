<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%delivery_times}}".
 *
 * @property int $id
 * @property string $title
 * @property int $zone_id
 * @property int $sort
 * @property int $status
 *
 * @property DeliveryTimeIntervals[] $deliveryTimeIntervals
 */
class DeliveryTimes extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%delivery_times}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['zone_id', 'sort', 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zone_id' => 'Зона',
            'title' => 'Заголовок',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getDeliveryTimeIntervals()
    {
        return $this->hasMany(DeliveryTimeIntervals::className(), ['target_id' => 'id'])->andWhere(['status' => 1])->orderBy(['sort' => SORT_ASC]);
    }

    public static function getZones() {
        $json = Modules::findOne(7)->html;
        $arr = ArrayHelper::toArray(Json::decode($json));
        $overlays = $arr['overlays'];
        $zones = ArrayHelper::map($overlays, 'id', 'shapeTitle');
        unset($zones['']);
        ksort($zones);
        return $zones;
    }

    public function getZoneTitle() {
        return $this::getZones()[$this->zone_id];
    }
}
