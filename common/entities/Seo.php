<?php

namespace common\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property int $id
 * @property string $page
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
class Seo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public function rules()
    {
        return [
            [['page'], 'required'],
            [['page'], 'string', 'max' => 50],
            [['meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['page'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
        ];
    }
}
