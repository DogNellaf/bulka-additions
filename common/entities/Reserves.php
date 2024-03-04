<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%reserves}}".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $restaurant_id
 * @property string $date
 * @property int $persons
 * @property string $notes
 * @property string $created_at
 * @property int $status
 */
class Reserves extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reserves}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'restaurant_id', 'date', 'persons'], 'required'],
            [['restaurant_id', 'persons', 'status'], 'integer'],
            [['notes'], 'string'],
            [['name', 'phone', 'date'], 'string', 'max' => 255],
            [['created_at'], 'string', 'max' => 11],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'restaurant_id' => 'Ресторан',
            'date' => 'Дата посещения',
            'persons' => 'Количество гостей',
            'notes' => 'Комментарии',
            'created_at' => 'Дата заявки',
            'status' => 'Статус',
        ];
    }

    public static function create($name, $phone, $restaurant_id, $date, $persons, $notes)
    {
        $entry = new static();
        $entry->name = $name;
        $entry->phone = $phone;
        $entry->restaurant_id = $restaurant_id;
        $entry->date = $date;
        $entry->persons = $persons;
        $entry->notes = $notes;
        $entry->save();
        return $entry;
    }
}
