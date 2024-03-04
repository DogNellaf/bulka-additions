<?php

namespace common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\SluggableBehavior;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%breakfast_products}}".
 *
 * @property int $id
 * @property int $category_id
 * @property int $title_icon
 * @property int $desc_icon
 * @property int $link_icon
 * @property int $price
 * @property string $title_ru
 * @property string $title_desc_ru
 * @property string $description_ru
 * @property string $additional_ru
 * @property string $llink_ru
 * @property string $href_ru
 * @property string $title_en
 * @property string $title_desc_en
 * @property string $description_en
 * @property string $additional_en
 * @property string $llink_en
 * @property string $href_en
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 * @property string $image_name
 * @property int $created_at
 * @property int $updated_at
 *
 */
class BreakfastProducts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%breakfast_products}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return BreakfastProducts::find()->where(['category_id' => $this->category_id]);
                }
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title_ru',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null,
            ]
        ];
    }

    public function rules()
    {
        return [
            [['category_id',], 'required'],
            [['category_id', 'price', 'sort'], 'integer'],
            [['description_ru', 'title_desc_ru', 'link_ru', 'href_ru','additional_ru', 'description_en', 'title_desc_en', 'link_en', 'href_en','additional_en'], 'string'],
            [['title_ru', 'title_en', 'image_name'], 'string', 'max' => 50],
            [['price',], 'integer'],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BreakfastCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['title_icon'], 'exist', 'skipOnError' => true, 'targetClass' => MenuIcons::class, 'targetAttribute' => ['title_icon' => 'id']],
            [['desc_icon'], 'exist', 'skipOnError' => true, 'targetClass' => MenuIcons::class, 'targetAttribute' => ['desc_icon' => 'id']],
            [['link_icon'], 'exist', 'skipOnError' => true, 'targetClass' => MenuIcons::class, 'targetAttribute' => ['link_icon' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'price' => 'Цена',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'status' => 'Статус',
            'title_icon' => 'Иконка названия продукта',
            'desc_icon' => 'Иконка описание продукта',
            'link_icon' => 'Иконка ссылки продукта',
            'title_ru' => 'Заголовок RU',
            'title_desc_ru' => 'Дополнение к названию RU',
            'description_ru' => 'Описание RU',
            'additional_ru' => 'Дополнительная информация RU',
            'link_ru' => 'Описание ссылки RU',
            'href_ru' => 'Адресс ссылки RU',
            'title_en' => 'Заголовок EN',
            'title_desc_en' => 'Дополнение к названию EN',
            'description_en' => 'Описание EN',
            'additional_en' => 'Дополнительная информация EN',
            'link_en' => 'Описание ссылки EN',
            'href_en' => 'Адресс ссылки EN',
        ];
    }



    public function getIconTitle()
    {
        return $this->hasOne(MenuIcons::class, ['id' => 'title_icon']);
    }

    public function getIconDesc()
    {
        return $this->hasOne(MenuIcons::class, ['id' => 'desc_icon']);
    }


    public function getIconLink()
    {
        return $this->hasOne(MenuIcons::class, ['id' => 'link_icon']);
    }



    public function getCategory()
    {
        return $this->hasOne(BreakfastCategories::class, ['id' => 'category_id']);
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
