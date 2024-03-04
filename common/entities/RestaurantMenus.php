<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%restaurant_menus}}".
 *
 * @property int $id
 * @property int $target_id
 * @property string $title
 * @property string $image_name
 * @property string $alt
 * @property string $doc_name
 * @property string $doc_name_en
 * @property int $sort
 * @property int $status
 *
 * @property Restaurants $target
 *
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 * @property UploadedFile $uploadedDocFile
 * @property  string $doc
 * @property  string $docPath
 * @property UploadedFile $uploadedDocEnFile
 * @property  string $docEn
 * @property  string $docEnPath
 */
class RestaurantMenus extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%restaurant_menus}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return RestaurantMenus::find()->where(['target_id' => $this->target_id]);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['target_id', 'title', 'image_name', 'doc_name'], 'required'],
            [['target_id', 'sort', 'status'], 'integer'],
            [['title', 'image_name', 'alt', 'doc_name', 'doc_name_en'], 'string', 'max' => 255],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurants::className(), 'targetAttribute' => ['target_id' => 'id']],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['uploadedDocFile'], 'safe'],
            [['uploadedDocFile'], 'file', 'extensions' => 'pdf, doc, docx, xls, xlsx, jpg, jpeg'],
            [['uploadedDocEnFile'], 'safe'],
            [['uploadedDocEnFile'], 'file', 'extensions' => 'pdf, doc, docx, xls, xlsx, jpg, jpeg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'target_id' => 'Ресторан',
            'title' => 'Заголовок',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'alt' => 'Alt',
            'doc_name' => 'PDF Ru',
            'uploadedDocFile' => 'PDF Ru',
            'doc_name_en' => 'PDF En',
            'uploadedDocEnFile' => 'PDF En',
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
    public $uploadedDocFile;
    public $uploadedDocEnFile;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            $this->uploadedDocFile = UploadedFile::getInstance($this, 'uploadedDocFile');
            $this->uploadedDocEnFile = UploadedFile::getInstance($this, 'uploadedDocEnFile');
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
            if ($this->uploadedDocFile) {
                if (!$this->isNewRecord) {
                    $this->deleteDoc();
                }
                $this->doc_name = $id .'_doc'. '.' . $this->uploadedDocFile->extension;
            }
            if ($this->uploadedDocEnFile) {
                if (!$this->isNewRecord) {
                    $this->deleteDocEn();
                }
                $this->doc_name_en = $id .'_doc_en'. '.' . $this->uploadedDocEnFile->extension;
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
        if ($this->uploadedDocFile) {
            $path = $this->_folderPath . $this->doc_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedDocFile->saveAs($path);
        }
        if ($this->uploadedDocEnFile) {
            $path = $this->_folderPath . $this->doc_name_en;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedDocEnFile->saveAs($path);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
        $this->deleteDoc();
        $this->deleteDocEn();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function deleteDoc()
    {
        if ($this->doc_name) {
            if (file_exists($this->_folderPath . $this->doc_name)) {
                unlink($this->_folderPath . $this->doc_name);
            }
        }
    }

    public function deleteDocEn()
    {
        if ($this->doc_name_en) {
            if (file_exists($this->_folderPath . $this->doc_name_en)) {
                unlink($this->_folderPath . $this->doc_name_en);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function removeDoc()
    {
        $this->deleteDoc();
        $this->doc_name = null;
        $this->save();
    }

    public function removeDocEn()
    {
        $this->deleteDocEn();
        $this->doc_name_en = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }

    public function getDoc()
    {
        return $this->_folder . $this->doc_name;
    }

    public function getDocEn()
    {
        return $this->_folder . $this->doc_name_en;
    }

}
