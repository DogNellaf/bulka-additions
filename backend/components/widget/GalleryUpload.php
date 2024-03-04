<?php
namespace backend\components\widget;

use trntv\filekit\widget\Upload;
use yii\helpers\Html;

class GalleryUpload extends Upload
{
    public function run()
    {
        $this->registerClientScript();
        $content = Html::beginTag('div');
        $content .= Html::hiddenInput($this->name, null, [
            'class' => 'empty-value',
            'id' => $this->options['id']
        ]);
        $content .= Html::fileInput($this->getFileInputName(), null, [
            'name' => $this->getFileInputName(),
            'id' => $this->getId(),
            'multiple' => $this->multiple,
            'accept' => 'image/*'
        ]);
        $content .= Html::endTag('div');
        return $content;
    }
}