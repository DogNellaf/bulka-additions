<?php

use frontend\components\Service;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model \frontend\forms\AccountForm */
/* @var $user \common\entities\User */

$user = Yii::$app->user->identity;
?>

<div id="personal" class="personal page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Личный кабинет'); ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="personal_nav_wrap animated">
            <div class="personal_nav_block">
                <ul class="personal_nav">
                    <li class="personal_nav_item">
                        <a href="#personal_data" class="common_btn active" data-block="personal_data">
                            Личные данные и адреса
                        </a>
                    </li>
                    <li class="personal_nav_item">
                        <a href="#history" class="common_btn" data-block="history">
                            История заказов
                        </a>
                    </li>
                    <li class="personal_nav_item">
                        <a href="#personal_favourites" class="common_btn" data-block="personal_favourites">
                            Избранное
                        </a>
                    </li>
                    <li class="personal_nav_item">
                        <a href="#loyalty_program" class="common_btn" data-block="loyalty_program">
                            Программа лояльности
                        </a>
                    </li>
                </ul>
                <div class="personal_logout">
                    <a href="<?= Url::to(['account/logout']); ?>" data-method="POST">
                        <i class="icon-logout"></i>
                        <span>Выйти</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="personal_block_wrap animated">
            <div id="personal_data" class="personal_block personal_data">
                <?php $form = ActiveForm::begin(); ?>
                <div class="check_info">
                    <div class="personal_data_ins">
                        <div class="top personal_data_row">
                            <div class="left">
                                <div class="personal_data_title">
                                    Личные данные
                                </div>
                                <div class="check_info_inputs">
                                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                                        'mask' => '+7 (999) 999-99-99',
                                    ]) ?>
                                    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                                </div>
                                <div class="submit_block">
                                    <?= Html::submitButton('Сохранить изменения', ['class' => 'submit common_btn']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="bottom personal_data_row">
                            <div class="left">
                                <div class="personal_data_title">
                                    Адреса доставки
                                </div>
                                <div class="personal_addresses_block">
                                    <div class="add-address">
                                        <a href="<?= Url::to(['account/add-address']); ?>" class="add-address-link">
                                            <span>Добавить адрес</span>
                                            <i></i>
                                        </a>
                                    </div>
                                    <ul class="personal_addresses_nav">
                                        <?php $i = 1; ?>
                                        <?php foreach ($model->addresses as $address): ; ?>
                                            <?php
                                            $address_title = '';
                                            if ($address->street) {
                                                $address_title .= $address->street . ', ';
                                            }
                                            if ($address->house) {
                                                $address_title .= $address->house . ', ';
                                            }
                                            if ($address->apartment) {
                                                $address_title .= $address->apartment;
                                            }
                                            if (!$address_title) {
                                                $address_title = '(Пусто)';
                                            }
                                            ?>
                                            <li class="personal_address_nav <?= ($i == 1) ? 'active' : ''; ?>">
                                                <span><?= $address_title; ?></span>
                                                <a href="<?= Url::to(['account/remove-address', 'id' => $address->id]); ?>">
                                                    <i class="icon-trash"></i>
                                                </a>
                                            </li>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="personal_addresses">
                                        <?php foreach ($model->addresses as $key => $address): ; ?>
                                            <div class="personal_address">
                                                <div class="check_info_inputs">
                                                    <?= $form->field($address, '[' . $key . ']street')->textInput(['maxlength' => 255]) ?>
                                                    <?= $form->field($address, '[' . $key . ']house')->textInput(['maxlength' => 255]) ?>
                                                    <?= $form->field($address, '[' . $key . ']apartment')->textInput(['maxlength' => 255]) ?>
                                                    <?= $form->field($address, '[' . $key . ']entrance')->textInput(['maxlength' => 255]) ?>
                                                    <?= $form->field($address, '[' . $key . ']intercom')->textInput(['maxlength' => 255]) ?>
                                                    <?= $form->field($address, '[' . $key . ']floor')->textInput(['maxlength' => 255]) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="submit_block">
                                    <?= Html::submitButton('Сохранить изменения', ['class' => 'submit common_btn']) ?>
                                </div>
                            </div>
                            <div class="right">
                                <div class="check_info_inputs">
                                    <div class="note_block">
                                        <div class="note_title">
                                            Комментарий к заказу
                                        </div>
                                        <div class="notes">
                                            <?php foreach ($model->addresses as $key => $address): ?>
                                                <div class="note personal_address_note">
                                                    <?= $form->field($address, '[' . $key . ']note')->textarea(['rows' => 7])->label(false) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="add_address_popup min_popup">
                    <div class="min_popup_ins">
                        <div class="min_popup_close">
                        </div>
                        <div class="min_popup_cont">
                        </div>
                    </div>
                </div>
            </div>
            <div id="history" class="personal_block history">
                <?php if ($user->activeOrders): ; ?>
                    <div class="history_info">
                        <div class="history_info_header history_row">
                            <div class="order_number">
                                Номер заказа
                            </div>
                            <div class="date">
                                Дата
                            </div>
                            <div class="status">
                                Статус
                            </div>
                            <div class="price">
                                Сумма
                            </div>
                            <div class="btn_block"></div>
                        </div>
                        <?php foreach ($user->activeOrders as $order): ; ?>
                            <div class="history_item_wrap">
                                <div class="history_item history_row">
                                    <div class="history_item_pda_title">
                                        Номер заказа
                                    </div>
                                    <div class="order_number">
                                        <?= $order->id; ?>
                                    </div>
                                    <div class="history_item_pda_title">
                                        Дата
                                    </div>
                                    <div class="date">
                                        <?= Yii::$app->formatter->asDate($order->created_at, 'dd.MM.yyyy'); ?>
                                    </div>
                                    <div class="history_item_pda_title">
                                        Статус
                                    </div>
                                    <div class="status">
                                        <?php
                                        //todo
                                        ?>
                                        <?//= $order::STATUSES[$order->status]; ?>
                                        Принят
                                    </div>
                                    <div class="history_item_pda_title">
                                        Сумма
                                    </div>
                                    <div class="price">
                                        <?= Service::formatPrice($order->cost) ?>
                                    </div>
                                    <div class="btn_block">
                                        <i class="icon-arrow-down"></i>
                                    </div>
                                </div>
                                <div class="history_item_detail">
                                    <div class="history_item_detail_ins">
                                        <div class="history_item_detail_info">
                                            <div class="history_item_detail_info_left history_item_detail_info_item">
                                                <div class="history_item_detail_item">
                                                    <div class="history_item_detail_item_title">
                                                        Адрес доставки
                                                    </div>
                                                    <div class="history_item_detail_item_cont">
                                                        <?php if ($order->name) : ?>
                                                            <p>
                                                                <?= $order->name; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if ($order->street || $order->house || $order->apartment || $order->floor || $order->entrance || $order->intercom) : ?>
                                                            <?php
                                                            $orderAddress = '';
                                                            $orderAddress .= ($order->street) ? $order->street . ', ' : '';
                                                            $orderAddress .= ($order->house) ? $order->house . ', ' : '';
                                                            $orderAddress .= ($order->apartment) ? $order->apartment . ', ' : '';
                                                            $orderAddress .= ($order->entrance) ? $order->entrance : '';
                                                            $orderAddress .= ($order->intercom) ? $order->intercom : '';
                                                            $orderAddress .= ($order->floor) ? $order->floor : '';
                                                            if (!$orderAddress) {
                                                                $orderAddress = '(Пусто)';
                                                            }
                                                            ?>
                                                            <p>
                                                                <?= $orderAddress; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if ($order->phone) : ?>
                                                            <p>
                                                                <?= $order->phone; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if ($order->email) : ?>
                                                            <p>
                                                                <?= $order->email; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="history_item_detail_info_right history_item_detail_info_item">
                                                <div class="history_item_detail_item">
                                                    <div class="history_item_detail_item_title">
                                                        Способ доставки
                                                    </div>
                                                    <div class="history_item_detail_item_cont">
                                                        <p>
                                                            <?= Yii::$app->params['deliveryMethods'][$order->delivery_method]; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="history_item_detail_item">
                                                    <div class="history_item_detail_item_title">
                                                        Дата и время
                                                    </div>
                                                    <div class="history_item_detail_item_cont">
                                                        <p>
                                                            <?= $order->delivery_date . '<br>' . $order->delivery_time; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="history_item_detail_item">
                                                    <div class="history_item_detail_item_title">
                                                        Способ оплаты
                                                    </div>
                                                    <div class="history_item_detail_item_cont">
                                                        <p>
                                                            <?= Yii::$app->params['payMethods'][$order->pay_method]; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="history_item_detail_orders_block">
                                            <?php $orderCart = \yii\helpers\Json::decode($order->cart_json, true); ?>
                                            <?php if (!empty($orderCart)): ?>
                                                <div class="history_detail_order_header history_detail_order_row">
                                                    <div class="history_detail_order_title">
                                                        Товары
                                                    </div>
                                                    <div class="history_detail_order_count">
                                                        Количество
                                                    </div>
                                                    <div class="history_detail_order_cost">
                                                        Стоимость
                                                    </div>
                                                </div>
                                                <?php foreach ($order->orderItems as $item) : ?>
                                                    <div class="history_detail_order history_detail_order_row">
                                                        <div class="history_detail_order_title">
                                                            <?php
                                                            $img = $item->product->image;
                                                            ?>
                                                            <div class="img" style="background-image: url(<?= $img; ?>)"></div>
                                                            <div class="name">
                                                                <div class="name_title"><?= $item->title; ?></div>
                                                                <?php if ($item->weight) : ?>
                                                                    <div class="weight">
                                                                        <?= $item->getWeight()->title; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="history_detail_order_pda_title">
                                                            Количество
                                                        </div>
                                                        <div class="history_detail_order_count">
                                                            <?= $item->qty_item; ?>
                                                        </div>
                                                        <div class="history_detail_order_pda_title">
                                                            Стоимость
                                                        </div>
                                                        <div class="history_detail_order_cost">
                                                            <?= Service::formatPrice($item->price_item); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="history_reorder_block">
                                                    <div class="reorder_link">
                                                        <a href="<?= Url::to(['cart/reorder', 'id' => $order->id]); ?>" class="common_btn">
                                                            Повторить заказ
                                                        </a>
                                                    </div>
                                                    <div class="history_order_delivery_info">
                                                        <span>Стоимость доставки:</span> <?= Service::formatPrice($order->delivery_cost); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div id="personal_favourites" class="personal_block personal_favourites">
                <div class="fav_list product_list">
                    <?php foreach ($user->favorites as $fav) : ?>
                        <?php if ($fav->product && $fav->product->status) : ?>
                            <?= $this->render('/catalog/_product', [
                                'product' => $fav->product,
                            ]); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="loyalty_program" class="personal_block loyalty_program">
                <?php if ($loyalty['is_registered'] == True) : ?>
                    <div class="loyalty-grid">
                        <div class="info-col">
                            <div class="card-info-grid">
                                <div class="info-block">
                                    <div class="title">
                                        Уровень
                                    </div>
                                    <div class="value">
                                        1
                                    </div>
                                </div>
                                <div class="info-block">
                                    <div class="title">
                                        Кэшбек
                                    </div>
                                    <div class="value">
                                        2%
                                    </div>
                                </div>
                                <div class="info-block">
                                    <div class="title">
                                        Баланс баллов
                                    </div>
                                    <div class="value">
                                        4 500
                                    </div>
                                </div>
                                <div class="info-block level-info">
                                    <div class="title">
                                        До следующего уровня
                                    </div>
                                    <div class="line-block">
                                        <div class="title-row">
                                            <div class="min">
                                                0 ₽
                                            </div>
                                            <div class="cur">
                                                40 000 ₽
                                            </div>
                                            <div class="max">
                                                100 000 ₽
                                            </div>
                                        </div>
                                        <div class="line-row">
                                            <div class="line">
                                                <div class="progress-line" style="width: 35%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="stats-block tabs-block">
                                <div class="tabs-head">
                                    <div class="tab-btn">
                                        История начислений и списаний
                                    </div>
                                </div>
                                <div class="tabs-body">
                                    <div class="tab-body active">
                                        <div class="info-row head-row">
                                            <div class="col">
                                                Дата
                                            </div>
                                            <div class="col">
                                                Дейстие
                                            </div>
                                            <div class="col">
                                                Сумма
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="col">
                                                10.04.2023
                                            </div>
                                            <div class="col">
                                                Начисление
                                            </div>
                                            <div class="col inc-col">
                                                + 1 000
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="col">
                                                09.04.2023
                                            </div>
                                            <div class="col">
                                                Списание
                                            </div>
                                            <div class="col dec-col">
                                                - 500
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-col">
                            <div class="card-block">
                                <div class="card-logo icon-logo"></div>
                                <div class="val-container" style="background: url(/files/front/images.jpg)">
                                    <div class="val-block">
                                        <div class="val">
                                            0
                                        </div>
                                        <div class="title">
                                            Баланс
                                        </div>
                                    </div>
                                    <div class="slogan font_2">
                                        Ешь настоящее, живи настоящим
                                    </div>
                                </div>
                                <div class="qr-block">
                                    <img src="/files/front/qr.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="ctrls-col">
                            <a href="<?= Url::to(['loyalty/wallet']); ?>" class="common_btn revert">
                                ДОБАВИТЬ В wallet
                            </a>
                            <a href="<?= Url::to(['loyalty/about']); ?>" class="loyalty-btn">
                                О программе лояльности
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-loyalty-block">
                        <h4>
                            <p><?= $loyalty['phone']; ?></p>
                            <p><?= Json::encode();; ?></p>
                            Вы пока не состоите в программе лояльности
                        </h4>
                        <div class="descr">
                            Зарегистрируйтесь, чтобы собирать бонусы и получать скидки
                        </div>
                        <a href="<?= Url::to(['loyalty/register']); ?>" class="common_btn">
                            ЗАРЕГИСТРИРОВАТЬСЯ
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
