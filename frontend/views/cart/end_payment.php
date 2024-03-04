<?php

use common\entities\Orders;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $orderId int|null */
/* @var $localOrderId int|null */

$this->title = "Ваш заказ оформлен";

?>
<div id="end_payment" class="end_payment page padded padded_bottom">
    <div class="page_header">
        <div class="wrapper">
            <div class="title">
                <?= $this->title; ?>
            </div>
        </div>
    </div>

    <div class="wrapper animated">
        <div class="end_payment_cont">
            <div class="img">
                <img src="/images/end_payment.svg" alt="">
            </div>

            <div class="text">
                Спасибо за Ваш заказ на сайте bulkabakery.ru!
            </div>

            <?php if ($localOrderId) : ?>
                <div class="order_info text">
                    <span>Номер Вашего заказа:</span>
                    <span class="order_id"><?= $localOrderId; ?></span>
                </div>
            <?php endif; ?>

            <div class="text">
                Ваш заказ уже принят в работу.
            </div>

            <?php if (!Yii::$app->user->isGuest) : ?>
                <div class="auth_user_info">
                    <span>Вы можете проверить его в Вашем</span> <a href="<?= Url::to(['/account/index', '#' => 'history']) ?>" class="lined">личном кабинете</a>
                </div>
            <?php endif; ?>

            <div class="text">
                Как правило, мы не перезваниваем для уточнения заказа. Поэтому если Вам нужно что-то изменить, свяжитесь с нами по телефону +7 (495) 230 7017 (доб. 4)
                или по электронной почте vopros@bulkabakery.ru
                <br>
                <br>
                Благодарим за доверие!
            </div>

            <div class="link">
                <a href="/" class="lined">
                    на главную
                </a>
            </div>
        </div>
    </div>
</div>

