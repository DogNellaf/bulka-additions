<?php

use common\entities\Contacts;
use common\entities\Socials;
use frontend\components\Service;
use yii\helpers\Html;
//use Yii;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
/* @var $order \common\entities\Orders */

$phone = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1])->andWhere(['type' => 'phone'])->one();
}, Yii::$app->params['cacheTime']);
$email = Contacts::getDb()->cache(function () {
    return Contacts::find()->having(['status' => 1])->andWhere(['type' => 'envelope'])->one();
}, Yii::$app->params['cacheTime']);
$inst = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->andWhere(['icon' => 'in'])->one();
}, Yii::$app->params['cacheTime']);
$fb = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->andWhere(['icon' => 'fb'])->one();
}, Yii::$app->params['cacheTime']);
$tw = Socials::getDb()->cache(function () {
    return Socials::find()->andWhere(['icon' => 'tw'])->one();
}, Yii::$app->params['cacheTime']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
</head>
<body style="font-family: 'Open Sans', sans-serif;font-size: 15px;font-weight: 400;letter-spacing: 0;color: #393939;">
<?php $this->beginBody() ?>
<?//= $content ?>
<div class="page" style="width: 800px;max-width:100%; margin: 0 auto;">
    <div class="wrapper" style="width: 600px;max-width:96%; margin: 0 auto;">
        <div class="logo_wrap" style="margin-top: 30px;margin-bottom: 50px;text-align: center;">
            <a href="<?= Yii::$app->urlManager->hostInfo; ?>" class="logo" style="text-decoration: none;outline: none !important;outline-offset: 0 !important;color: #000;width: 150px;max-width: 100%;display: inline-block;">
                <img src="<?= Yii::$app->urlManager->hostInfo . '/images/logo-mail.png'; ?>" alt="" style="width: 100%;">
            </a>
        </div>
        <div class="header_title" style="margin-bottom: 33px;font-size: 20px;font-weight: 500;text-align: center;text-transform: uppercase;">
            Подтверждение заказа
        </div>
        <div class="header_subtitle" style="margin-bottom: 18px; padding-bottom: 6px;line-height: 1.8;border-bottom: 1px solid #868686;">
            <p>
                Добрый день, <?= $order->name; ?>,
            </p>
            <p>
                Спасибо за Ваш заказ на сайте bulkabakery.ru!
            </p>
            <p>
                Вы можете отслеживать заказ в вашем
                <a href="<?= Yii::$app->urlManager->hostInfo . '/account/index'; ?>"
                   target="_blank"
                   style="color: #BA9732; text-decoration: underline;"
                >личном кабинете</a>.
            </p>
            <p>
                Как правило, мы не перезваниваем для уточнения заказа. Поэтому если Вам нужно что-то изменить, свяжитесь с нами по телефону +7 (495) 230 7017 (доб. 4) или по электронной почте vopros@bulkabakery.ru
            </p>
            <p>
                Благодарим за доверие!
            </p>
        </div>
        <div class="order_info" style="margin-bottom: 20px;border-bottom: 1px solid #868686;">
            <table style="width: 100%; line-height: 1.1;">
                <tr>
                    <td style="font-size: 18px; font-weight: 500;padding-bottom: 15px;">
                        Номер заказа:
                    </td>
                    <td style="font-size: 18px; font-weight: 500;text-align: right;padding-bottom: 15px;">
                        <?= $order->id; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px;font-weight: 500;padding-bottom: 15px;">
                        Дата заказа:
                    </td>
                    <td style="font-size: 15px;text-align: right;padding-bottom: 15px;">
                        <?= date('d.m.Y H:i', $order->created_at); ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px;font-weight: 500;padding-bottom: 15px;">
                        Время доставки/самовывоза:
                    </td>
                    <td style="font-size: 15px;text-align: right;padding-bottom: 15px;">
                        <?= $order->delivery_date . ' ' . $order->delivery_time; ?>
                    </td>
                </tr>
                <?php if ($order->delivery_method == 'delivery') : ?>
                    <tr>
                        <td style="font-size: 15px;font-weight: 500;padding-bottom: 15px;">
                            Адрес заказа:
                        </td>
                        <td style="font-size: 15px;text-align: right;padding-bottom: 15px;">
                            <?php if ($order->street || $order->apartment || $order->floor) : ?>
                                <?php
                                $orderAddress = '';
                                $orderAddress .= ($order->street) ? $order->street . ', ' : '';
                                $orderAddress .= ($order->apartment) ? $order->apartment . ', ' : '';
                                $orderAddress .= ($order->apartment) ? $order->floor : '';
                                ?>

                                <?= $orderAddress; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td style="font-size: 15px;font-weight: 500;padding-bottom: 15px;">
                            Точка самовывоза:
                        </td>
                        <td style="font-size: 15px;text-align: right;padding-bottom: 15px;">
                            <?php
                            $pickup_point_title = \common\entities\Restaurants::findOne($order->delivery_pickup_point)->title;
                            ?>
                            <?= $pickup_point_title; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="font-size: 15px;font-weight: 500;padding-bottom: 15px;">
                        Комментарии к заказу:
                    </td>
                    <td style="font-size: 15px;text-align: right;padding-bottom: 15px;">
                        <?= $order->note; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="title" style="font-size: 18px;font-weight: 500;margin-bottom: 7px;">
            ВАШ ЗАКАЗ
        </div>
        <div class="order_items_list" style="border-bottom: 1px solid #868686; margin-bottom: 20px;">
            <?php foreach ($order->orderItems as $item) : ?>
                <div class="order_item order_items_row" style="padding: 14px 0;border-bottom: 1px solid #DCDCDC;">
                    <div class="title" style="width: 67%;display: inline-block;vertical-align: middle;">
                        <img src="<?= Yii::$app->urlManager->hostInfo . $item->product->image; ?>" style="width: 90px;float: left;">
                        <div class="name" style="padding-left: 16px;float: left;">
                            <div class="name_title">
                                <?= $item->title; ?>
                            </div>
                            <?php
                            /*
                            ?>
                            <div class="name_options" style="margin-top: 8px;">
                                <?= $item->getWeight()->title; ?>
                            </div>
                            <?php
                            */
                            ?>
                        </div>
                        <div class="clr" style="clear: both;height: 0;line-height: 0;display: block;float: none;padding: 0;margin: 0;border: none;"></div>
                    </div>
                    <div class="count" style="width: 14%;text-align: center;display: inline-block;vertical-align: middle;">
                        <?= $item->qty_item; ?>
                    </div>
                    <div class="price" style="width: 16%;text-align: right;display: inline-block;vertical-align: middle;">
                        <?= Service::formatPrice($item->price_item) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="order_items_cost" style="margin-bottom: 30px;border-bottom: 1px solid #868686;">
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 15px; font-weight: 500; padding-bottom: 15px;">
                        Количество позиций:
                    </td>
                    <td style="font-size: 15px; padding-bottom: 15px; text-align: right;">
                        <?= $order->quantity; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px; font-weight: 500; padding-bottom: 15px;">
                        Стоимость доставки
                    </td>
                    <td style="font-size: 15px; padding-bottom: 15px; text-align: right;">
                        <?= Service::formatPrice($order->delivery_cost) ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px; font-weight: 500; padding-bottom: 15px;">
                        Способ оплаты:
                    </td>
                    <td style="font-size: 15px; padding-bottom: 15px; text-align: right;">
                        <?= Yii::$app->params['payMethods'][$order->pay_method]; ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 18px; font-weight: 500; padding-bottom: 15px;">
                        Стоимость заказа:
                    </td>
                    <td style="font-size: 18px; font-weight: 500; padding-bottom: 15px; text-align: right;">
                        <?= Service::formatPrice($order->cost + $order->delivery_cost) ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="line" style="margin-bottom: 20px;border-bottom: 1px solid #DBD8D1;"></div>
    <div class="wrapper" style="width: 600px;max-width:96%; margin: 0 auto;">
        <div class="contacts" style="margin-bottom: 30px;">
            <table style="width: 100%; font-size: 14px;font-weight: 500;">
                <tr>
                    <td style="width: 33.3333%;">
                        <a href="tel:<?= $phone->value; ?>" style="text-decoration: none;outline: none !important;outline-offset: 0 !important;color: #000;position: relative;">
                            <?= $phone->value; ?>
                        </a>
                    </td>
                    <td style="width: 33.3333%;">
                        <div class="socials" style="text-align: center;">
                            <table style="max-width: 100%;margin: 0 auto;">
                                <tr>
                                    <td style="padding: 0 12px;">
                                        <a href="<?= $fb->link; ?>" style="color: #BA9732;" target="_blank">
                                            <img src="<?= Yii::$app->urlManager->hostInfo . '/images/mail-fb.png'; ?>" alt="" style="height: 20px;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 12px;">
                                        <a href="<?= $inst->link; ?>" style="color: #BA9732;" target="_blank">
                                            <img src="<?= Yii::$app->urlManager->hostInfo . '/images/mail-inst.png'; ?>" alt="" style="height: 20px;">
                                        </a>
                                    </td>
                                    <td style="padding: 0 12px;">
                                        <a href="<?= $tw->link; ?>" style="color: #BA9732;" target="_blank">
                                            <img src="<?= Yii::$app->urlManager->hostInfo . '/images/mail-tw.png'; ?>" alt="" style="height: 20px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td style="width: 33.3333%;text-align: right;">
                        <a href="mailto:<?= $email->value; ?>" style="text-decoration: none;outline: none !important;outline-offset: 0 !important;color: #000;position: relative;">
                            <?= $email->value; ?>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
