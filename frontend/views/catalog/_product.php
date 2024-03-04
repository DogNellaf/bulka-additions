<?php
use yii\helpers\Url;

/* @var $product \common\entities\Products */

$url = Url::to(['catalog/product', 'slug' => $product->slug]);
?>

<div class="item product_list_item animated" data-id="<?= $product->id; ?>">
    <a href="<?= $url; ?>">
        <div class="img img_hover_item">
            <img src="<?= $product->image; ?>" alt="">
        </div>
    </a>
    <div class="descr">
        <div class="cont">
            <a href="<?= $url; ?>" class="title">
                <?= $product->title; ?>
            </a>
            <div class="price">
                <?= \frontend\components\Service::formatPrice($product->getProductBasePrice()); ?>
            </div>
        </div>
        <div class="counts">
            <a href="<?= Url::to(['cart/minus', 'id' => $product->id, 'weight' => $product->getProductBaseWeight()->id]); ?>"
               data-href="<?= Url::to(['cart/minus', $product->id, 'weight' => $product->getProductBaseWeight()->id]); ?>"
               class="count_btn count cartButton">
                -
            </a>
            <span class="quantity count">1</span>
            <a href="<?= Url::to(['cart/plus', 'id' => $product->id, 'weight' => $product->getProductBaseWeight()->id]); ?>"
               data-href="<?= Url::to(['cart/plus', 'id' => $product->id, 'weight' => $product->getProductBaseWeight()->id]); ?>"
               class="count_btn count cartButton">
                +
            </a>
        </div>
    </div>
</div>
