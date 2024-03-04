<?php

use yii\helpers\Url;

/* @var $cart array */
/* @var $id int */
/* @var $sum int */
?>

<div class="page basket">

    <div class="order-info order-view">
        <?php if (!empty($cart)): ?>
            <div class="order-item jq_hidden">
                <div class="item-head item-row">
                    <div class="title">
                        Наименование
                    </div>
                    <div class="count">
                        Количество
                    </div>
                    <div class="price">
                        Стоимость
                    </div>
                    <div class="add-block"></div>
                </div>
                <?php foreach ($cart as $key => $item):; ?>
                    <div class="item-info item-row">
                        <div class="title">
                            <div class="image-block">
                                <div class="image cover-bg"
                                     style="background-image: url(<?= $item['image']; ?>)"></div>
                            </div>
                            <h3><?= $item['title']; ?></h3>
                        </div>
                        <div class="count">
                            <div class="count-val">
                                <?= $item['quantity']; ?>
                            </div>
                        </div>
                        <div class="price">
                            <?= $item['cost'] ?> ₽
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="order-summary item-row jq_hidden">
            <div class="title">
                ОБЩАЯ СУММА
            </div>
            <div class="count">
            </div>
            <div class="price">
                <?= $sum; ?> ₽
            </div>
        </div>

        <div class="add-links jq_hidden">
            <a href="<?= Url::to(['cart/reorder', 'id' => $id]); ?>" class="line-link"><span
                        class="icon-back-link"></span> Повторить заказ
            </a>
        </div>
    </div>
</div>
