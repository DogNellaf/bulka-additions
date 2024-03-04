<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%restaurants}}".
 *
 * @property int $id
 * @property string $title
 * @property string $image_name
 * @property string $alt
 * @property string $slug
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $worktime
 * @property string $lat
 * @property string $lng
 * @property string $additional_info
 * @property int $sort
 * @property int $status
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property RestaurantMenus[] $restaurantMenus
 * @property RestaurantTexts[] $restaurantTexts
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class Restaurants extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%restaurants}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
                'immutable' => true,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'image_name', 'slug', 'address', 'phone', 'email', 'worktime', 'lat', 'lng'], 'required'],
            [['additional_info', 'meta_description'], 'string'],
            [['sort', 'status'], 'integer'],
            [['title', 'image_name', 'alt', 'slug', 'address', 'phone', 'worktime', 'lat', 'lng', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'alt' => 'Alt',
            'slug' => 'Slug',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'worktime' => 'Время работы',
            'lat' => 'Широта',
            'lng' => 'Долгота',
            'additional_info' => 'Список особенностей',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
        ];
    }

    public function getRestaurantMenus()
    {
        return $this->hasMany(RestaurantMenus::className(), ['target_id' => 'id']);
    }

    public function getRestaurantTexts()
    {
        return $this->hasMany(RestaurantTexts::className(), ['target_id' => 'id']);
    }

    #################### IMAGES ####################

    private $imageWidth = 1920;
    private $imageHeight = null;
    private $quality = 100;

    public function __construct(array $config = [])
    {
        $folderName = str_replace(['{', '}', '%'], '', $this::tableName());
        parent::__construct($config);
        $this->_folder = '/files/' . $folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . $folderName . '/';
    }

    public $uploadedImageFile;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            if ($this->isNewRecord) {
                /* @var $lastModel self */
                $lastModel = self::find()->orderBy(['id' => SORT_DESC])->one();
                $id = intval($lastModel->id + 1);
            } else {
                $id = $this->id;
            }
            if ($this->uploadedImageFile) {
                if (!$this->isNewRecord) {
                    $this->deleteImage();
                }
                $this->image_name = $id .'_'. time() . '.' . $this->uploadedImageFile->extension;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->uploadedImageFile) {
            $path = $this->_folderPath . $this->image_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedImageFile->saveAs($path);
            if ($this->uploadedImageFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => $this->quality]);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }
}
