<?php
use yii\helpers\Url;
use yii\widgets\Menu;

/* @var $menuItems1 array */
/* @var $menuItems2 array */
/* @var $phone \common\entities\Contacts */
/* @var $socials \common\entities\Socials[] */

$isHome = ((Yii::$app->controller->id === Yii::$app->defaultRoute) && (Yii::$app->controller->action->id === Yii::$app->controller->defaultAction)) ? true : false;
$transparent_header = '';
if ($isHome
    || ((Yii::$app->controller->id === 'site') && (Yii::$app->controller->action->id === 'restaurants'))
    || ((Yii::$app->controller->id === 'site') && (Yii::$app->controller->action->id === 'restaurant'))
) {
    $transparent_header = 'transparent';
}
?>

<!--HEADER-->
<div id="header" class="<?= $transparent_header; ?> animated">
<!--    <div class="header_notify">-->
<!--        Сайт находится в тестовом режиме. Если у Вас возникли проблемы, Вы можете перейти на <a href="http://old.bulkabakery.ru/" target="_blank">старую версию сайта</a>-->
<!--    </div>-->
    <div class="header_desk">
        <div class="wrapper">
            <div class="header_ins">
                <div class="header_left header_side">
                    <div class="header_top">
                        <a href="tel:<?= $phone->value; ?>" class="header_phone">
                            <i class="icon-phone"></i>
                            <span><?= $phone->value; ?></span>
                        </a>
                    </div>
                    <div class="nav">
                        <?= Menu::widget([
                            'items' => $menuItems1,
                            'encodeLabels' => false,
                            'options' => [
                                'class' => 'nav_list',
                            ],
                            'itemOptions' => [
                                'class' => 'nav_item',
                            ],
                        ]); ?>
                    </div>
                </div>
                <a href="/" class="logo">
                    <i class="icon-logo"></i>
                </a>
                <div class="header_right header_side">
                    <div class="header_top">
                        <a href="#" class="header_reserve_btn reserve_popup_btn">
                            Резерв
                        </a>
                        <div class="header_btns">
                            <a href="#" class="header_search_btn header_btn search_popup_btn">
                                <i class="icon-search"></i>
                            </a>
                        </div>
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
                    <div class="nav">
                        <?= Menu::widget([
                            'items' => $menuItems2,
                            'encodeLabels' => false,
                            'options' => [
                                'class' => 'nav_list',
                            ],
                            'itemOptions' => [
                                'class' => 'nav_item',
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header_pda">
        <div class="wrapper">
            <div class="header_pda_ins">
                <a href="/" class="pda_logo">
                    <i class="icon-logo"></i>
                </a>
                <a href="<?= Url::to(['/cart/index']) ?>" class="cart-link header_pda_cart_btn">
                    Корзина (<span class="cart-qty"><?= Yii::$container->get('common\models\Cart')->getTotalAmount(); ?></span>)
                </a>
                <div class="header_btns">
                    <a href="#" class="header_btn">
                        <i class="icon-phone"></i>
                    </a>
                </div>
                <div class="pda_menu_btn">
                    <div class="pda_menu_btn_ins">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="notification_cart_popup" class="cart_popup">
        <div class="cart_popup_cont">
            <div class="cart_popup_ins"></div>
        </div>
    </div>
</div>

<div class="pda_menu">
    <div class="pda_menu_ins">
        <div id="submenu">
            <?= Menu::widget([
                'items' => $menuItems1,
                'encodeLabels' => false,
                'options' => [],
            ]); ?>
            <?= Menu::widget([
                'items' => $menuItems2,
                'encodeLabels' => false,
                'options' => [],
            ]); ?>
        </div>
        <div class="clr"></div>
        <div class="menu_header_btns">
            <div class="header_btns">
                <a href="#" class="header_search_btn header_btn search_popup_btn">
                    <i class="icon-search"></i>
                </a>
            </div>
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
        <div class="header_reserve_btn_wrap">
            <a href="#" class="header_reserve_btn">
                Резерв
            </a>
        </div>
    </div>
</div>