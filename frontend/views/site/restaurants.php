<?php
use yii\helpers\Url;


/* @var $module \common\entities\Modules */
/* @var $restaurants \common\entities\Restaurants[] */
/* @var $this yii\web\View */
?>

<div id="restaurants" class="restaurants page padded_bottom">

    <div class="restaurants_top page_top animated">
        <div class="bg">
            <img src="<?= $module->image; ?>" alt="<?= $module->alt; ?>">
        </div>
        <div class="cont">
            <div class="title font_2 page_top_title">
                <?= \frontend\components\Service::strSplit('Рестораны'); ?>
            </div>
        </div>
        <div class="button_down_wrap">
            <div class="button_down scroll_down_btn">
                <i class="icon-chevron-down"></i>
            </div>
        </div>
    </div>
    
    <div class="restaurants_list">
        <?php foreach ($restaurants as $restaurant) : ?>
            <div class="restaurants_list_item">
                <div class="img">
                    <a href="<?= Url::to(['site/restaurant', 'slug' => $restaurant->slug]); ?>" class="img_hover_item">
                        <img src="<?= $restaurant->image; ?>" alt="<?= $restaurant->alt; ?>">
                    </a>
                </div>
                <div class="cont">
                    <div class="ins">
                        <div class="title font_2 title_2">
                            <?= \frontend\components\Service::strSplit($restaurant->title); ?>
                        </div>
                        <div class="descr animated">
                            <div class="info">
                                <div class="address info_item">
                                    <?= $restaurant->address; ?>
                                </div>
                                <div class="phone info_item">
                                    <a href="tel:<?= $restaurant->phone; ?>">
                                        <?= $restaurant->phone; ?>
                                    </a>
                                </div>
                                <div class="worktime info_item">
                                    <?= $restaurant->worktime; ?>
                                </div>
                            </div>
                            <div class="addit_info">
                                <?= $restaurant->additional_info; ?>
                            </div>
                        </div>
                        <div class="readmore">
                            <a href="<?= Url::to(['site/restaurant', 'slug' => $restaurant->slug]); ?>" class="common_btn_2">
                                Меню
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
