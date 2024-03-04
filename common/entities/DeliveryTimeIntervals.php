<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%delivery_time_intervals}}".
 *
 * @property int $id
 * @property int $target_id
 * @property string $start
 * @property string $end
 * @property int $cost
 * @property int $sort
 * @property int $status
 *
 * @property DeliveryTimes $target
 */
class DeliveryTimeIntervals extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%delivery_time_intervals}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return DeliveryTimeIntervals::find()->where(['target_id' => $this->target_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['target_id', 'start', 'end'], 'required'],
            [['target_id', 'cost', 'sort', 'status'], 'integer'],
            [['start', 'end'], 'string', 'max' => 50],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryTimes::className(), 'targetAttribute' => ['target_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target_id' => 'Тип',
            'start' => 'Начало',
            'end' => 'Конец',
            'cost' => 'Стоимость',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getTarget()
    {
        return $this->hasOne(DeliveryTimes::className(), ['id' => 'target_id']);
    }
}
