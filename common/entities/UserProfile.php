<?php

namespace common\entities;

use trntv\filekit\behaviors\UploadBehavior;
use yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $picture
 * @property string $avatar
 * @property string $avatar_path
 * @property string $avatar_base_url
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    public function behaviors()
    {
        return [
            'picture' => [
                'class' => UploadBehavior::class,
                'attribute' => 'picture',
                'pathAttribute' => 'avatar_path',
                'baseUrlAttribute' => 'avatar_base_url'
            ]
        ];
    }

    public $picture;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['avatar_path', 'avatar_base_url'], 'string', 'max' => 255],
            ['picture', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'picture' => 'Аватар',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public function getFullName()
    {
        return $this->user->username;
    }

    public function getAvatar($default = null)
    {
        return $this->avatar_path
            ? $this->avatar_base_url . str_replace('\\', '/', $this->avatar_path)
            : $default;
    }
}