<?php

namespace common\entities;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property int $id
 * @property string $type
 * @property string $value
 * @property int $sort
 * @property int $status
 */
class Contacts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    const VARIANTS = [
        'phone' => 'Телефон',
        'envelope' => 'Почта',
        'point' => 'Адрес',
        'clock' => 'Время работы',
        'other' => 'Другое',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
//                'scope' => function () {
//                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'string'],
            [['status', 'status'], 'integer'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип данных',
            'value' => 'Значение',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }
}
