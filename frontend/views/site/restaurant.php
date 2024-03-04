<?php
use yii\helpers\Url;

/* @var $restaurant \common\entities\Restaurants */
/* @var $relRestaurant \common\entities\Restaurants */
/* @var $this yii\web\View */
?>
<div id="restaurant" class="restaurant page">

    <div class="restaurant_top page_top animated">
        <div class="bg">
            <img src="<?= $restaurant->image; ?>" alt="<?= $restaurant->alt; ?>">
        </div>
        <div class="cont">
            <div class="title font_2 page_top_title">
                <?= \frontend\components\Service::strSplit($restaurant->title); ?>
            </div>
        </div>
        <div class="button_down_wrap">
            <div class="button_down scroll_down_btn">
                <i class="icon-chevron-down"></i>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <?php if ($restaurant->restaurantTexts) : ?>
            <?php $restaurantFirstText = $restaurant->restaurantTexts[0]; ?>
            <div class="restaurant_item">
                <div class="img">
                    <img src="<?= $restaurantFirstText->image; ?>" alt="<?= $restaurantFirstText->alt; ?>" class="anim_rot_img">
                </div>
                <div class="cont">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit($restaurantFirstText->title); ?>
                    </div>
                    <div class="text animated">
                        <?= $restaurantFirstText->html; ?>
                    </div>
                    <?php if ($restaurantFirstText->link) : ?>
                        <div class="link animated">
                            <a href="<?= $restaurantFirstText->link; ?>" class="lined">
                                <?= $restaurantFirstText->link_title; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($restaurant->restaurantMenus) : ?>
            <div class="restaurant_menu_block">
                <div class="restaurant_menu_title title_1 font_2">
                    <?= \frontend\components\Service::strSplit('Меню'); ?>
                </div>
                <div class="restaurant_menu_list">
                    <?php foreach ($restaurant->restaurantMenus as $restaurantMenu) : ?>
                        <div class="restaurant_menu_item animated">
                            <div class="bg img_hover_item">
                                <img src="<?= $restaurantMenu->image; ?>" alt="<?= $restaurantMenu->alt; ?>">
                            </div>
                            <div class="cont">
                                <div class="title">
                                    <?= $restaurantMenu->title; ?>
                                </div>
                                <div class="links">
                                    <a href="<?= $restaurantMenu->doc; ?>" target="_blank" class="link common_btn">
                                        Меню на русском
                                    </a>
                                    <?php if ($restaurantMenu->doc_name_en) : ?>
                                        <a href="<?= $restaurantMenu->docEn; ?>" target="_blank" class="link common_btn">
                                            English menu
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($restaurant->restaurantTexts && count($restaurant->restaurantTexts) > 1) : ?>
            <?php $i = 1; ?>
            <?php foreach ($restaurant->restaurantTexts as $restaurantText) : ?>
                <?php if ($i == 1) {
                    $i++;
                    continue;
                } ?>
                <div class="restaurant_item_2 restaurant_item <?= (($i % 2) == 0) ? 'even' : ''; ?>">
                    <div class="img">
                        <img src="<?= $restaurantText->image; ?>" alt="<?= $restaurantText->alt; ?>" class="anim_rot_img">
                    </div>
                    <div class="cont">
                        <div class="title title_1 font_2">
                            <?= \frontend\components\Service::strSplit($restaurantText->title); ?>
                        </div>
                        <div class="text animated">
                            <?= $restaurantText->html; ?>
                        </div>
                        <?php if ($restaurantText->link) : ?>
                            <div class="link animated">
                                <a href="<?= $restaurantText->link; ?>" class="lined">
                                    <?= $restaurantText->link_title; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="restaurant_contacts animated">
        <div class="wrapper">
            <div class="ins">
                <div class="address item">
                    <div class="title">
                        Адрес
                    </div>
                    <div class="cont">
                        <?= $restaurant->address; ?>
                    </div>
                </div>
                <div class="phone item">
                    <div class="title">
                        Телефон
                    </div>
                    <div class="cont">
                        <a href="tel:<?= $restaurant->phone; ?>">
                            <?= $restaurant->phone; ?>
                        </a>
                    </div>
                </div>
                <div class="worktime item">
                    <div class="title">
                        Время работы
                    </div>
                    <div class="cont">
                        <?= $restaurant->worktime; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($relRestaurant) : ?>
        <div class="restaurant_more_block">
            <div class="wrapper">
                <div class="ins">
                    <div class="restaurant_more_title">
                        Посмотрите также
                    </div>
                    <div class="restaurant_more">
                        <div class="restaurant_more_item">
                            <a href="<?= Url::to(['site/restaurant', 'slug' => $relRestaurant->slug]); ?>">
                                <div class="img img_hover_item">
                                    <img src="<?= $relRestaurant->image; ?>" alt="<?= $relRestaurant->alt; ?>">
                                </div>
                                <div class="cont">
                                    <div class="title title_1 font_2">
                                        <?= \frontend\components\Service::strSplit($relRestaurant->title); ?>
                                    </div>
                                    <div class="arrow">
                                        <i class="icon-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
