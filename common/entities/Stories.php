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
 * This is the model class for table "{{%stories}}".
 *
 * @property int $id
 * @property string $title
 * @property string $html
 * @property string $image_name
 * @property string $video_name
 * @property string $slug
 * @property int $sort
 * @property int $status
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property Stories|null $prev
 * @property Stories|null $next
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 * @property UploadedFile $uploadedVVideoFile
 * @property string $video
 */
class Stories extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%stories}}';
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
            [['title', 'html', 'image_name', 'slug'], 'required'],
            [['html', 'meta_description'], 'string'],
            [['sort', 'status'], 'integer'],
            [['title', 'image_name', 'video_name', 'slug', 'meta_title', 'meta_keywords'], 'string'],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['uploadedVideoFile'], 'safe'],
            [['uploadedVideoFile'], 'file', 'extensions' => 'mp4'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'html' => 'Текст',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'video_name' => 'Видео',
            'uploadedVideoFile' => 'Видео',
            'slug' => 'Slug',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
        ];
    }

    public function getPrev()
    {
        /* @var $story Stories */
        if (!$story = Stories::findOne(['sort' => ($this->sort - 1)])) {
            return null;
        }
        return $story;
    }

    public function getNext()
    {
        /* @var $story Stories */
        if (!$story = Stories::findOne(['sort' => ($this->sort + 1)])) {
            return null;
        }
        return $story;
    }

    #################### IMAGES ####################
    ################ and VIDEOS ####################

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
    public $uploadedVideoFile;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            $this->uploadedVideoFile = UploadedFile::getInstance($this, 'uploadedVideoFile');

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

            if ($this->uploadedVideoFile) {
                if (!$this->isNewRecord) {
                    $this->deleteVideo();
                }
                $this->video_name = $id .'_'. time() . '.' . $this->uploadedVideoFile->extension;
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

        if ($this->uploadedVideoFile) {
            $path = $this->_folderPath . $this->video_name;

            FileHelper::createDirectory(dirname($path, 1));

            $this->uploadedVideoFile->saveAs($path);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
        $this->deleteVideo();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function deleteVideo()
    {
        if ($this->video_name) {
            if (file_exists($this->_folderPath . $this->video_name)) {
                unlink($this->_folderPath . $this->video_name);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function removeVideo()
    {
        $this->deleteVideo();
        $this->video_name = null;
        $this->save();
    }

    public function getImage()
    {
        return (!empty($this->image_name))
            ? $this->_folder . $this->image_name
            : '';
    }

    public function getVideo()
    {
        return (!empty($this->video_name))
            ? $this->_folder . $this->video_name
            : '';
    }
}
