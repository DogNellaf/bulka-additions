<?php
use yii\helpers\Url;
use yii\widgets\Menu;

/* @var $gallery \common\entities\Galleries */
/* @var $galleries \common\entities\Galleries[] */
/* @var $this yii\web\View */

$menuItems = [];
foreach ($galleries as $navGallery) {
    $menuItems[] = ['label' => $navGallery->title, 'url' => ['site/gallery', 'slug' => $navGallery->slug]];
}
?>

<div id="gallery" class="gallery page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Галерея'); ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="common_nav animated">
            <?= Menu::widget([
                'items' => $menuItems,
                'encodeLabels' => false,
                'options' => [
                    'class' => 'list',
                ],
                'itemOptions' => [
                    'class' => 'item',
                ],
            ]); ?>
        </div>
        
        <div class="gallery_list">
            <?php foreach ($gallery->galleryAttachments as $attachment) : ?>
                <div class="gallery_item">
                    <img src="<?= $attachment->getUrl(); ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
