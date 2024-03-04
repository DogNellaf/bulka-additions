<?php

namespace common\entities;

use backend\components\SortableBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%chef}}".
 *
 * @property int $id
 * @property string $slug
 * @property string $name_ru
 * @property string $description_ru
 * @property string $link_ru
 * @property string $href_ru
 * @property string $name_en
 * @property string $description_en
 * @property string $link_en
 * @property string $href_en
 * @property int $status
 * @property string $image_name
 * @property int $created_at
 * @property int $updated_at
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 *
 */
class Chef extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chef}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title_ru',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name_ru',], 'required'],
            [['status', 'sort'], 'integer'],
            [['description_ru', 'name_ru', 'link_ru', 'href_ru', 'description_en', 'name_en', 'link_en', 'href_en'], 'string'],
            [['image_name'], 'string', 'max' => 100],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'status' => 'Статус',
            'name_ru' => 'ФИО RU',
            'description_ru' => 'Описание RU',
            'link_ru' => 'Описание ссылки RU',
            'href_ru' => 'Адресс ссылки RU',
            'name_en' => 'ФИО EN',
            'description_en' => 'Описание EN',
            'link_en' => 'Описание ссылки EN',
            'href_en' => 'Адресс ссылки EN',
        ];
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
            if ($this->uploadedImageFile) {
                if (!$this->isNewRecord) {
                    $this->deleteImage();
                }
                if (!$this->image_name) {
                    /* @var $lastModel self */
                    $lastModel = self::find()->orderBy(['id' => SORT_DESC])->one();
                    $id = $lastModel->id + 1;
                } else {
                    $id = $this->id;
                }
                $this->image_name = $id . '_'. time() . '.' . $this->uploadedImageFile->extension;
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
