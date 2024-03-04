<?php
use frontend\assets\MapAsset;
MapAsset::register($this);

/* @var $this yii\web\View */
/* @var $restaurants \common\entities\Restaurants[] */

?>

<div id="contacts" class="contacts page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Контакты'); ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="contacts_nav common_nav animated">
            <div class="list">
                <?php $i = 1; ?>
                <?php foreach ($restaurants as $restaurant) : ?>
                    <div class="contacts_nav_item item <?= ($i == 1) ? 'active' : ''; ?>">
                        <a href="#" data-contact="<?= $restaurant->id; ?>">
                            <?= $restaurant->title; ?>
                        </a>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="contacts_block">
            <div class="cont">
                <div class="contacts_list animated">
                    <?php foreach ($restaurants as $restaurant) : ?>
                        <div class="contacts_list_item" data-contact="<?= $restaurant->id; ?>">
                            <div class="contact_item">
                                <i class="icon-location"></i>
                                <span><?= $restaurant->address; ?></span>
                            </div>
                            <br>
                            <a href="tel:<?= $restaurant->phone; ?>" class="contact_item">
                                <i class="icon-phone"></i>
                                <span><?= $restaurant->phone; ?></span>
                            </a>
                            <br>
                            <div class="contact_item">
                                <i class="icon-clock"></i>
                                <span><?= $restaurant->worktime; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="contacts_form_block animated">
                    <div class="contacts_form_title">
                        Напишите нам
                    </div>
                    <div class="contacts_form">
                        <?= $this->render('/layouts/_callback_form'); ?>
                    </div>
                </div>
            </div>
            <div class="map_block animated">
                <?php foreach ($restaurants as $restaurant) : ?>
                    <div class="map" data-contact="<?= $restaurant->id; ?>">
                        <div id="map_place_<?= $restaurant->id; ?>"
                             class="map_place"
                             data-lat="<?= $restaurant->lat; ?>"
                             data-lng="<?= $restaurant->lng; ?>"
                             data-id="<?= $restaurant->id; ?>"
                        ></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
