<?php

use common\models\CartItem;
use frontend\components\Service;
use yii\helpers\Url;

/**
 * @var $item CartItem
 * @var $message string
 * @var $data \common\entities\Products
 * @var $quantity integer
 */
?>

<div id="cartRender" data-id="<?= $data->id; ?>" data-amount="<?= Yii::$container->get('common\models\Cart')->getAmount(); ?>">
    <a href="<?=Url::to(['/cart/index'])?>" class="cart_popup_item">
        <div class="image">
            <img src="<?= $data->image ?>" alt="">
        </div>
        <div class="cart_popup_header">
            <?= $message; ?>
        </div>
        <div class="title">
            <?= $data->title; ?>
            <?= ($item->weight) ? ' - ' . $item->getWeightTitle() : ''; ?>

            <?php if ($quantity) : ?>
                <div class="count">
                    <?= $quantity; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($quantity) : ?>
            <div class="price">
                <?= Service::formatPrice($item->getCost()) ?>
            </div>
        <?php endif; ?>
    </a>
</div>