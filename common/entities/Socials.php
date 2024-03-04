<?php

namespace common\entities;

use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%socials}}".
 *
 * @property integer $id
 * @property string $link
 * @property string $icon
 * @property integer $sort
 * @property integer $status
 */
class Socials extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%socials}}';
    }

    const VARIANTS = [
        'fb' => 'Facebook',
        'in' => 'Instagram',
        'vk' => 'Vkontakte',
        'tw' => 'Twitter',
        'you' => 'Youtube'
    ];

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
            [['icon', 'link'], 'required'],
            [['icon'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 100],
            [['sort'], 'integer', 'max' => 11],
            [['status'], 'integer', 'max' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Ссылка',
            'icon' => 'Соцсеть',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }
}