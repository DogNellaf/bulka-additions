<?php

use yii\helpers\Url;
use frontend\assets\VideoJSAsset;
VideoJSAsset::register($this);

/* @var $story \common\entities\Stories */
/* @var $this yii\web\View */
?>
<div id="story" class="story page padded padded_bottom">
    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit($story->title); ?>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="story_block">
            <?if($story->video != ''):?>
            <div class="video animated">
                <div class="video-player">
                    <video
                        id="bulka-player"
                        class="video-js"
                        controls
                        preload="auto"
                        height="420"
                        poster=""
                        data-setup="{}"
                    >
                        <source src="<?= $story->video;?>" type="video/mp4" />
                        <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                    </video>
                </div>
            </div>
            <?endif;?>
            <div class="text animated">
                <?= $story->html; ?>
            </div>
            <div class="story_links animated">
                <div class="story_link prev">
                    <?php if ($story->prev) : ?>
                        <a href="<?= Url::to(['site/story', 'slug' => $story->prev->slug]); ?>">
                            <i class="icon-arrow-left"></i>
                            <span>предыдущая история</span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="story_link return">
                    <a href="<?= Url::to(['site/stories']); ?>">
                        <span>назад</span>
                    </a>
                </div>
                <div class="story_link next">
                    <?php if ($story->next) : ?>
                        <a href="<?= Url::to(['site/story', 'slug' => $story->next->slug]); ?>">
                            <span>следующая история</span>
                            <i class="icon-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>