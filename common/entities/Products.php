<?php

namespace common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\SluggableBehavior;
use backend\components\SortableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property int $price
 * @property string $weight
 * @property string $description
 * @property string $image_name
 * @property string $proteins
 * @property string $fats
 * @property string $carbohydrates
 * @property string $kcal
 * @property int $status
 * @property int $category_status
 * @property int $sort
 * @property int $created_at
 * @property int $updated_at
 * @property int $mainpage_status
 * @property int $min_delivery_days
 * @property int $id_1c
 * @property string $sku
 * @property string $rel_products
 *
 * @property Products[] $relProducts
 * @property ProductWeights[] $productWeights
 * @property ProductOptions[] $productOptions
 * @property ProductCategories $category
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class Products extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%products}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return Products::find()->where(['category_id' => $this->category_id]);
                }
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
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
            [['category_id', 'title', 'description', 'image_name'], 'required'],
            [['category_id', 'price'], 'integer'],
            [['description'], 'string'],
            [['title', 'slug', 'image_name', 'weight'], 'string', 'max' => 50],
            [['proteins', 'fats', 'carbohydrates', 'kcal'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            ['uploadedImageFile', 'required', 'when' => function () {
                return !$this->image_name;
            }, 'whenClient' => true],
            [['mainpage_status'], 'integer'],
            [['min_delivery_days'], 'integer'],
            [['id_1c'], 'integer'],
            [['sku'], 'string', 'max' => 255],
            //[['rel_products'], 'string', 'max' => 255],
            [['rel_products'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'proteins' => 'Белки',
            'fats' => 'Жиры',
            'carbohydrates' => 'Углеводы',
            'kcal' => 'Ккал',
            'price' => 'Цена',
            'description' => 'Описание',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'weight' => 'Вес',
            'mainpage_status' => 'На главную',
            'min_delivery_days' => 'Дней на доставку, минимум 0',
            'id_1c' => '1C ID',
            'sku' => 'SKU',
            'rel_products' => 'Похожие товары',
        ];
    }

    //todo remove all base product weight?
    public function getProductWeight()
    {
        $arr = explode('/', trim(str_replace(['г', 'g'], '', $this->weight)));
        $result = 0;
        foreach ($arr as $item) {
            $result += is_numeric(trim($item)) ? $item : 0;
        }
        return $result;
    }

    public function getProductOptions()
    {
        return $this->hasMany(ProductOptions::className(), ['product_id' => 'id'])->orderBy('sort');
    }

    public function getProductBaseWeight()
    {
        if ($this->productWeights) {
            return $this->productWeights[array_key_first($this->productWeights)];
        }
        return null;
    }

    public function getProductBasePrice()
    {
        if ($this->getProductBaseWeight()) {
            /* @var $user \common\entities\User */
            $user = Yii::$app->user->identity;
            if (!Yii::$app->user->isGuest && $user->isBusinessWholesale()) {
                return $this->getProductBaseWeight()->business_price;
            }
            return $this->getProductBaseWeight()->price;
        }
        return $this->price;
    }

    public function getProductBasePriceWithWeight($weight_id)
    {
        if ($productWeight = ProductWeights::findOne($weight_id)) {
            /* @var $user \common\entities\User */
            $user = Yii::$app->user->identity;
            if (!Yii::$app->user->isGuest && $user->isBusinessWholesale()) {
                return $productWeight->business_price;
            }
            return $productWeight->price;
        }
        return $this->price;
    }

    public function getProductWeights()
    {
        return $this->hasMany(ProductWeights::className(), ['product_id' => 'id'])->andWhere(['status' => 1])->orderBy(['sort' => SORT_ASC]);
    }

    public function getCategory()
    {
        return $this->hasOne(ProductCategories::class, ['id' => 'category_id']);
    }

    public function isFavorite($userId){
        if (!isset($userId)){
            return false;
        }
        $fav = UserFavorites::find()->where(['user_id'=>$userId,'product_id'=>$this->id])->one();
        if ($fav!=null){
            return true;
        }
        return false;
    }

    public function getRelProductsIds()
    {
        if (!$this->rel_products)
            return false;
        $relProductsIds = ArrayHelper::toArray(Json::decode($this->rel_products));
        if (!$relProductsIds)
            return false;
        return $relProductsIds;
    }

    public function getRelProducts()
    {
        if (!$relProductsIds = $this->getRelProductsIds())
            return false;
        return Products::find()->andWhere(['id' => $relProductsIds, 'status' => 1, 'category_status' => 1])->orderBy('sort')->all();
    }

    public function getProductsIds()
    {
        $ids = Json::decode($this->rel_products);
        if (!$ids)
            return [];
        return $ids;
    }

    public function getProductsList()
    {
        $ids = $this->getProductsIds();
        if (!$ids || !is_array($ids))
            return [];

        $data = Products::find()->andWhere(['id' => $ids])->orderBy([new Expression(sprintf("FIELD(id, %s)", implode(",", $ids)))])->all();
        return $data;
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
