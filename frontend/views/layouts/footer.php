<?php
use yii\helpers\Url;

/* @var $phone \common\entities\Contacts */
/* @var $email \common\entities\Contacts */
/* @var $socials \common\entities\Socials[] */
/* @var $bonus_docs \common\entities\BonusDocs */
/* @var $module_14 \common\entities\Modules */
?>

<div id="footer" class="footer animated">
    <div class="wrapper">
        <div class="ins">
            <a href="/" class="footer_logo">
                <i class="icon-logo"></i>
            </a>
            <div class="footer_cont">
                <div class="footer_cont_top">
                    <div class="footer_item">
                        <div class="title">
                            Контакты
                        </div>
                        <div class="cont links_list">
                            <ul>
                                <li class="phone">
                                    <a href="tel:<?= $phone->value; ?>">
                                        <?= $phone->value; ?>
                                    </a>
                                </li>
                                <li class="mail">
                                    <a href="mailto:<?= $email->value; ?>">
                                        <?= $email->value; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php if($socials){?>
                        <div class="footer_item">
                            <div class="title">
                                Социальные сети
                            </div>
                            <div class="cont">
                                <div class="socials">
                                    <ul>
                                        <?php foreach ($socials as $social): ; ?>
                                            <li>
                                                <a href="<?= $social->link;?>" target="_blank">
                                                    <i class="icon-<?= $social->icon;?>"></i>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    <div class="footer_item">
                        <div class="title">
                            Бонусная программа
                        </div>
                        <div class="cont links_list bonus-list">
                            <ul>
                                <li>
                                    <a href="<?= Url::to(['site/bonus']); ?>">
                                        <?= $module_14->title;?>
                                    </a>
                                </li>
                                <?php foreach ($bonus_docs as $doc): ; ?>
                                    <li>
                                        <a href="/files/bonus_docs/<?= $doc->image_name;?>" target="_blank">
                                            <?= $doc->title;?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="footer_item">
                        <div class="title">
                            Принимаем к оплате
                        </div>
                        <div class="cont pay_logos_list">
                            <img src="/images/pay_logos/visa_inc_logo.png" alt="">
                            <img src="/images/pay_logos/mastercard-logo.png" alt="">
                            <img src="/images/pay_logos/mir-logo.png" alt="">
                        </div>
                    </div>
                    <div class="footer_item">
                        <div class="title">
                            Разработка сайта
                        </div>
                        <div class="dev">
                            <a href="https://otlr.digital/" target="_blank">
                                <i class="icon-otlr"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="footer_cont_bottom">
                    <div class="copy">
                        &copy; <?= Yii::$app->name; ?>, <?= date('Y'); ?>. <?= Yii::t('app', 'All rights reserved.'); ?>
                    </div>
                    <div class="footer_policy_link_wrap">
                        <a href="<?= Url::to(['site/policy']); ?>" class="footer_policy_link lined">
                            Политика конфиденциальности
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
