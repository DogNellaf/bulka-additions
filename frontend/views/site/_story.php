<?php
use yii\helpers\Url;

/* @var $story \common\entities\Stories */
?>

<li class="item story_list_item animated">
    <a href="<?= Url::to(['site/story', 'slug' => $story->slug]); ?>">
        <div class="bg img_hover_item">
            <img src="<?= $story->image; ?>" alt="">
        </div>
        <div class="cont">
            <div class="title">
                <?= $story->title; ?>
            </div>
            <div class="link common_btn_2 white">
                Подробнее
            </div>
        </div>
    </a>
</li>
