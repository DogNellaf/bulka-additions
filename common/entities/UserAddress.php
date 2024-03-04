<?php

namespace common\entities;

use common\entities\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 * @property string $street
 * @property string $house
 * @property string $apartment
 * @property string $floor
 * @property string $entrance
 * @property string $intercom
 * @property string $note
 *
 * @property User $user
 */
class UserAddress extends ActiveRecord
{
    const SCENARIO_MACHINE = 'machine';

    public static function tableName()
    {
        return '{{%user_address}}';
    }

    public function scenarios()
    {
        return [
            $this::SCENARIO_MACHINE => ['user_id'],
            $this::SCENARIO_DEFAULT => ['user_id', 'value', 'street', 'house', 'apartment', 'floor', 'entrance', 'intercom', 'note'],
        ];
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['value', 'street', 'house', 'apartment', 'floor', 'entrance', 'intercom'], 'string', 'max' => 255],
            [['note'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'value' => 'Адрес',
            'street' => 'Улица',
            'house' => 'Дом',
            'apartment' => 'Квартира/офис',
            'floor' => 'Этаж',
            'intercom' => 'Домофон',
            'entrance' => 'Подъезд',
            'note' => 'Комментарий',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}