<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%callbacks}}".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $notes
 * @property string $created_at
 * @property int $status
 */
class Callbacks extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%callbacks}}';
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
            [['name', 'phone', 'email', 'notes'], 'required'],
            [['notes'], 'string'],
            [['status'], 'integer'],
            [['name', 'phone', 'email'], 'string', 'max' => 255],
            [['created_at'], 'string', 'max' => 11],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'notes' => 'Текст',
            'created_at' => 'Дата',
            'status' => 'Статус',
        ];
    }

    public static function create($name, $phone, $email, $notes)
    {
        $entry = new static();
        $entry->name = $name;
        $entry->phone = $phone;
        $entry->email = $email;
        $entry->notes = $notes;
        $entry->save();
        return $entry;
    }
}
