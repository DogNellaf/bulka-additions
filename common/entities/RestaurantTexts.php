<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%restaurant_texts}}".
 *
 * @property int $id
 * @property int $target_id
 * @property string $title
 * @property string $link
 * @property string $link_title
 * @property string $html
 * @property string $image_name
 * @property string $alt
 * @property int $sort
 * @property int $status
 *
 * @property Restaurants $target
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class RestaurantTexts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%restaurant_texts}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return RestaurantTexts::find()->where(['target_id' => $this->target_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['target_id', 'title', 'html', 'image_name'], 'required'],
            [['target_id', 'sort', 'status'], 'integer'],
            [['html'], 'string'],
            [['title', 'link', 'link_title', 'image_name', 'alt'], 'string', 'max' => 255],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurants::className(), 'targetAttribute' => ['target_id' => 'id']],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target_id' => 'Ресторан',
            'title' => 'Заголовок',
            'link' => 'Ссылка',
            'link_title' => 'Текст ссылки',
            'html' => 'Текст',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'alt' => 'Alt',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getTarget()
    {
        return $this->hasOne(Restaurants::className(), ['id' => 'target_id']);
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
