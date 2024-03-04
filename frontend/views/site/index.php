<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $mainSlider \common\entities\MainSlider[] */
/* @var $module_1 \common\entities\Modules */
/* @var $module_2 \common\entities\Modules */
/* @var $module_3 \common\entities\Modules */
/* @var $module_4 \common\entities\Modules */
/* @var $module_4_2 \common\entities\Modules */
/* @var $module_5 \common\entities\Modules */
/* @var $productsSlider \common\entities\Products[] */
/* @var $stories \common\entities\Stories[] */

?>
<div id="mainpage" class="mainpage page">

    <div class="main_slider animated">
        <ul class="list">
            <?php foreach ($mainSlider as $mainSlide): ?>
                <li class="item">
                    <div class="bg" style="background-image: url(<?= $mainSlide->image; ?>)"></div>
                    <div class="cont">
                        <?php if ($mainSlide->title) : ?>
                            <div class="title font_2 page_top_title">
                                <?= \frontend\components\Service::strSplit($mainSlide->title); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($mainSlide->link) : ?>
                            <a href="<?= $mainSlide->link; ?>" class="link common_btn_2 white">
                                Подробнее
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="clr"></div>
        <div class="button_down_wrap">
            <div class="button_down scroll_down_btn">
                <i class="icon-chevron-down"></i>
            </div>
        </div>
        <div class="slider_nav_block">
            <div class="slider_nav prev"><i class="icon-arrow-left"></i></div>
            <div class="slider_counter">
                <div class="current">
                    01
                </div>
                <span>/</span>
                <div class="quantity">
                    <?php
                    $sliderLength = count($mainSlider);
                    $sliderLength = ($sliderLength < 10) ? '0' . $sliderLength : $sliderLength;
                    echo $sliderLength;
                    ?>
                </div>
            </div>
            <div class="slider_nav next"><i class="icon-arrow-right"></i></div>
        </div>
    </div>

    <div class="main_2 main_block">
        <div class="wrapper">
            <div class="ins">
                <div class="img">
                    <img src="<?= $module_1->image; ?>" alt="<?= $module_1->alt; ?>" class="anim_rot_img">
                </div>
                <div class="cont">
                    <div class="title font_2 title_1">
                        <?= \frontend\components\Service::strSplit($module_1->title); ?>
                    </div>
                    <div class="text animated">
                        <?= $module_1->html; ?>
                    </div>
                    <?php if ($module_1->link) : ?>
                        <div class="link_wrap">
                            <a href="<?= $module_1->link; ?>" class="link lined">
                                Подробнее
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="cont_img">
                        <img src="<?= $module_1->image2; ?>" alt="<?= $module_1->alt2; ?>" class="anim_rot_img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main_recommend">
        <div class="wrapper">
            <div class="main_recommend_title title_1 font_2">
                <?= \frontend\components\Service::strSplit($module_2->title); ?>
            </div>
            <div class="main_recommend_text animated">
                <?= $module_2->html; ?>
            </div>
            <div class="main_recommend_slider">
                <div class="slider_navig animated">
                    <div class="slider_nav prev"><i class="icon-arrow-left"></i></div>
                    <div class="slider_nav next"><i class="icon-arrow-right"></i></div>
                </div>
                <div class="list product_list">
                    <?php foreach ($productsSlider as $productSlide): ?>
                        <?= $this->render('/catalog/_product', [
                            'product' => $productSlide,
                        ]); ?>
                    <?php endforeach; ?>
                </div>
                <div class="clr"></div>
            </div>
        </div>
    </div>

    <div class="main_photo_1 main_photo img_scroll_wrap">
        <div class="img_scroll">
            <img src="<?= $module_3->image; ?>" alt="<?= $module_3->alt; ?>">
        </div>
    </div>

    <div class="main_3 main_block">
        <div class="wrapper">
            <div class="ins">
                <div class="img">
                    <img src="<?= $module_4->image; ?>" alt="<?= $module_4->alt; ?>" class="anim_rot_img">
                </div>
                <div class="cont">
                    <div class="title font_2 title_1">
                        <?= \frontend\components\Service::strSplit($module_4->title); ?>
                    </div>
                    <div class="text animated">
                        <?= $module_4->html; ?>
                    </div>
                    <?php if ($module_4->link) : ?>
                        <div class="link_wrap">
                            <a href="<?= $module_4->link; ?>" class="link lined white">
                                Подробнее
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="cont_img">
                        <img src="<?= $module_4->image2; ?>" alt="<?= $module_4->alt2; ?>" class="anim_rot_img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main_3_2 main_block">
        <div class="wrapper">
            <div class="ins">
                <div class="img">
                    <img src="<?= $module_4_2->image; ?>" alt="<?= $module_4_2->alt; ?>" class="anim_rot_img">
                </div>
                <div class="cont">
                    <div class="title font_2 title_1">
                        <?= \frontend\components\Service::strSplit($module_4_2->title); ?>
                    </div>
                    <div class="text animated">
                        <?= $module_4_2->html; ?>
                    </div>
                    <?php if ($module_4_2->link) : ?>
                        <div class="link_wrap">
                            <a href="<?= $module_4_2->link; ?>" class="link lined">
                                Подробнее
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="cont_img">
                        <img src="<?= $module_4_2->image2; ?>" alt="<?= $module_4_2->alt2; ?>" class="anim_rot_img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main_photo_2 main_photo img_scroll_wrap">
        <div class="img_scroll">
            <img src="<?= $module_5->image; ?>" alt="<?= $module_5->alt; ?>">
        </div>
    </div>

    <div class="main_stories">
        <div class="main_stories_title title_1 font_2">
            <?= \frontend\components\Service::strSplit('Наши истории'); ?>
        </div>
        <div class="main_stories_slider">
            <ul class="list">
                <?php foreach ($stories as $story): ?>
                    <?= $this->render('_story', [
                        'story' => $story,
                    ]); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
