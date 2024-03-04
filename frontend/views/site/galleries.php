<?php

use yii\helpers\Url;

/* @var $galleries \common\entities\Galleries[] */
/* @var $this yii\web\View */
?>

<div id="galleries" class="galleries page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Галерея'); ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="galleries_list">
            <?php foreach ($galleries as $gallery) : ?>
                <a href="<?= Url::to(['site/gallery', 'slug' => $gallery->slug]); ?>" class="galleries_list_item animated">
                    <div class="bg img_hover_item">
                        <img src="<?= $gallery->image; ?>" alt="<?= $gallery->alt; ?>">
                    </div>
                    <div class="cont">
                        <div class="title">
                            <?= $gallery->title; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="clr"></div>
    </div>

</div>
