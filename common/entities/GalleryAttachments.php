<?php

namespace common\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%gallery_attachments}}".
 *
 * @property int $id
 * @property int $article
 * @property string $path
 * @property string $base_url
 * @property string $type
 * @property int $size
 * @property string $name
 * @property int $order
 *
 * @property Galleries $article0
 */
class GalleryAttachments extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%gallery_attachments}}';
    }

    public function rules()
    {
        return [
            [['article', 'size', 'order'], 'integer'],
            [['path', 'base_url', 'type', 'name'], 'string', 'max' => 255],
            [['article'], 'exist', 'skipOnError' => true, 'targetClass' => Galleries::className(), 'targetAttribute' => ['article' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Article',
            'path' => 'Path',
            'base_url' => 'Base Url',
            'type' => 'Type',
            'size' => 'Size',
            'name' => 'Name',
            'order' => 'Order',
        ];
    }

    public function getArticle0()
    {
        return $this->hasOne(Galleries::className(), ['id' => 'article']);
    }

    public function getUrl()
    {
        return $this->base_url . str_replace("\\", "/", $this->path);
    }
}
