<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%modules}}".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $html
 * @property string $link
 * @property string $image_name
 * @property string $alt
 * @property string $image_name_2
 * @property string $alt2
 * @property int $min_order_sum
 * @property int $min_free_delivery_sum
 * @property int $min_free_delivery_sum_outer_mkad
 *
 * @property UploadedFile uploadedImageFile
 * @property string $image
 * @property UploadedFile uploadedImage2File
 * @property string $image2
 */
class Modules extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%modules}}';
    }

    public function rules()
    {
        return [
            [['html', 'description'], 'string'],
            [['title', 'link', 'alt', 'alt2'], 'string', 'max' => 255],
            [['image_name', 'image_name_2'], 'string', 'max' => 50],
            [['min_order_sum', 'min_free_delivery_sum', 'min_free_delivery_sum_outer_mkad'], 'integer'],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['uploadedImage2File'], 'safe'],
            [['uploadedImage2File'], 'file', 'extensions' => 'png, jpg, jpeg'],
//            ['uploadedImageFile', 'required', 'when' => function () {
//                return !$this->image_name;
//            }, 'whenClient' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'html' => 'Текст',
            'link' => 'Ссылка',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'image_name_2' => 'Изображение 2',
            'uploadedImage2File' => 'Изображение 2',
            'min_order_sum' => 'Минимальная сумма заказа',
            'min_free_delivery_sum' => 'Сумма бесплатной доставки в пределах МКАД',
            'min_free_delivery_sum_outer_mkad' => 'Сумма бесплатной доставки за пределы МКАД',
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
    public $uploadedImage2File;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            $this->uploadedImage2File = UploadedFile::getInstance($this, 'uploadedImage2File');
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
            if ($this->uploadedImage2File) {
                if (!$this->isNewRecord) {
                    $this->deleteImage2();
                }
                $this->image_name_2 = $id .'_img2_'. time() . '.' . $this->uploadedImage2File->extension;
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
        if ($this->uploadedImage2File) {
            $path = $this->_folderPath . $this->image_name_2;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedImage2File->saveAs($path);
            if ($this->uploadedImage2File->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => $this->quality]);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
        $this->deleteImage2();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function deleteImage2()
    {
        if ($this->image_name_2) {
            if (file_exists($this->_folderPath . $this->image_name_2)) {
                unlink($this->_folderPath . $this->image_name_2);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function removeImage2()
    {
        $this->deleteImage2();
        $this->image_name_2 = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }

    public function getImage2()
    {
        return $this->_folder . $this->image_name_2;
    }
}
