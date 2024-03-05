<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Service;

/* @var $this yii\web\View */
/* @var $cart \common\models\Cart */
$this->title = "Корзина";

/* @var $user \common\entities\User */
$user = Yii::$app->user->identity;

/* @var $cost_module \common\entities\Modules */
$cost_module = \common\entities\Modules::findOne(9);
?>

<script src="/js/vendor/jquery.min.js" type="text/javascript"></script>


<div id="cart" class="cart page padded padded_bottom">
<input id="demo_0" type="text" name="" value="" class="irs-hidden-input" tabindex="-1" readonly="">
<script>
 $("#demo_0").ionRangeSlider({
        min: 100,
        max: 1000,
        from: 550
    });
</script>

<!-- <script src="/js/vendor/imagesloaded.pkgd.min.js" type="text/javascript"></script>
<script src="/js/vendor/slick.min.js" type="text/javascript"></script>
<script src="/js/vendor/ion.rangeSlider.js" type="text/javascript"></script>
<script src="/js/vendor/jquery.suggestions.min.js" type="text/javascript"></script>
<script src="/js/card-script.js" type="text/javascript"></script> -->
    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
            </div>
        </div>
    </div>

    <div class="cart_block_wrap animated">
        <div class="wrapper">
            <div class="cart_block">
                <div class="cart_row_header cart_row">
                    <div class="title">
                        товар
                    </div>
                    <div class="options_col">
                        опции
                    </div>
                    <div class="count_col">
                        количество
                    </div>
                    <div class="price">
                        стоимость
                    </div>
                    <div class="date">
                        ближайшая дата доставки
                    </div>
                    <div class="del">

                    </div>
                </div>
                <div class="cart_items">
                    <?php foreach ($cart->getItems() as $item):
                        /* @var  $item \common\models\CartItem */
                        $product = $item->getProduct();
                        $url = Url::to(['catalog/product', 'slug' => $product->slug]);
                        ?>
                        <div class="cart_item cart_row">
                            <div class="cart_item_pda_title">
                                товар
                            </div>
                            <div class="title">
                                <a class="img" href="<?= $url ?>">
                                    <img src="<?= $product->image_name ? $product->image : '/images/default_thumb.png'; ?>" alt="">
                                </a>
                                <div class="cont">
                                    <a class="name" href="<?= $url ?>">
                                        <?= Html::encode($product->title) ?>
                                    </a>
                                    <?php if ($item->weight) : ?>
                                        <div class="weight">
                                            <?= $item->getWeightTitle(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="cart_item_pda_title">
                                опции
                            </div>
                            <div class="options_col">
                                <?php if ($product->productOptions) : ?>
                                    <div class="cart_options">
                                        <div class="cart_opts">
                                            <div class="cart_item_modify_block">
                                                <div class="cart_item_opts_list">
                                                    <?php foreach ($item->getOptions() as $option) : ?>
                                                        <div class="cart_item_opt">
                                                            <?= $option->title; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="cart_item_modify_opts">
                                                    <div class="cart_item_modify_opts_list">
                                                        <?php foreach ($product->productOptions as $option) : ?>
                                                            <?php
                                                            $checked = '';
                                                            if (in_array($option->id, $item->getOptionsIds())) {
                                                                $checked = 'checked';
                                                            }
                                                            ?>
                                                            <label class="option cartProductOption">
                                                                <input type="checkbox" name="product-option"
                                                                       value="<?= $option->id; ?>"
                                                                       data-price="<?= $option->getPrice(); ?>"
                                                                    <?= $checked; ?>
                                                                >
                                                                <i></i>
                                                                <span class="option_title"><?= $option->title; ?></span>
                                                                <!--<span class="option_price">- <?/*= Service::formatPrice($option->price) */?></span>-->
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <a href="<?= Url::to(['cart/update-options', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options, 'newOptions' => $item->options]); ?>"
                                                       data-href="<?= Url::to(['cart/update-options', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                                       class="cart_update_options cartUpdate cartButton common_btn">
                                                        Изменить опции
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart_options_update_btn cart_item_modify_btn">
                                                <i class="icon-note"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                            <div class="cart_item_pda_title">
                                количество
                            </div>
                            <div class="count_col">
                                <div class="counts">
                                    <a href="<?= Url::to(['cart/minus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       data-href="<?= Url::to(['cart/minus', $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       class="count_btn count cartButton">
                                        -
                                    </a>
                                    <span class="quantity count"><?= $item->getQuantity() ?></span>
                                    <a href="<?= Url::to(['cart/plus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       data-href="<?= Url::to(['cart/plus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       class="count_btn count cartButton">
                                        +
                                    </a>
                                </div>
                            </div>
                            <div class="cart_item_pda_title">
                                стоимость
                            </div>
                            <div class="price">
                                <?= Service::formatPrice($item->getCost()) ?>
                            </div>
                            <div class="cart_item_pda_title">
                                ближайшая дата доставки
                            </div>
                            <div class="date">
                                <?php
                                $min_delivery_date_timestamp = time();
                                if ($item->getProduct()->min_delivery_days) {
                                    $min_delivery_date_timestamp += $item->getProduct()->min_delivery_days * 24 * 3600;
                                }
                                $min_delivery_date = Yii::$app->formatter->asDatetime($min_delivery_date_timestamp, 'dd MMMM');
                                echo $min_delivery_date;
                                ?>
                            </div>
                            <div class="cart_item_pda_title">
                            </div>
                            <div class="del">
                                <a class="cart-remove"
                                   href="<?= Url::to(['cart/remove', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]) ?>" data-method="post">
                                    <span class="icon-delete"></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart_bottom">
                    <div class="left">
                        <?php if (Yii::$app->user->isGuest || !$user->isBusinessWholesale()) : ?>
                            <p>
                                Минимальная сумма заказа: <?= Service::formatPrice($cost_module->min_order_sum) ?>
                            </p>
                            <p>
                                Бесплатная доставка в пределах МКАД: от <?= Service::formatPrice($cost_module->min_free_delivery_sum) ?>
                            </p>
                            <p>
                                Бесплатная доставка за пределы МКАД: от <?= Service::formatPrice($cost_module->min_free_delivery_sum_outer_mkad) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="center">
                        <p>
                            Доступно бонусов: <strong>4 449</strong>
                        </p>
                        <div class="bonuses-block">
                            Списать:
                            <div class="bones-container">
                                <div class="range-block">
                                    <span class="irs irs--flat js-irs-0 irs-with-grid">
                                        <span class="irs">
                                            <span class="irs-line" tabindex="0"></span>
                                            <span class="irs-min" style="visibility: hidden;">0</span>
                                            <span class="irs-max" style="visibility: visible;">4 999</span>
                                            <span class="irs-from" style="visibility: hidden;">0</span>
                                            <span class="irs-to" style="visibility: hidden;">0</span>
                                            <span class="irs-single" style="left: 2.86766%;">0</span>
                                        </span>
                                        <span class="irs-grid" style="width: 89.1241%; left: 5.33794%;">
                                            <span class="irs-grid-pol" style="left: 0%"></span>
                                            <span class="irs-grid-text js-grid-text-0" style="left: 0%; margin-left: 0%;">0</span>
                                            <span class="irs-grid-pol small" style="left: 20%"></span>
                                            <span class="irs-grid-pol small" style="left: 15%"></span>
                                            <span class="irs-grid-pol small" style="left: 10%"></span>
                                            <span class="irs-grid-pol small" style="left: 5%"></span>
                                            <span class="irs-grid-pol" style="left: 25%"></span>
                                            <span class="irs-grid-text js-grid-text-1" style="left: 25%; visibility: visible; margin-left: 0%;">1 250</span>
                                            <span class="irs-grid-pol small" style="left: 45%"></span>
                                            <span class="irs-grid-pol small" style="left: 40%"></span>
                                            <span class="irs-grid-pol small" style="left: 35%"></span>
                                            <span class="irs-grid-pol small" style="left: 30%"></span>
                                            <span class="irs-grid-pol" style="left: 50%"></span>
                                            <span class="irs-grid-text js-grid-text-2" style="left: 50%; visibility: visible; margin-left: 0%;">2 500</span>
                                            <span class="irs-grid-pol small" style="left: 70%"></span>
                                            <span class="irs-grid-pol small" style="left: 65%"></span>
                                            <span class="irs-grid-pol small" style="left: 60%"></span>
                                            <span class="irs-grid-pol small" style="left: 55%"></span>
                                            <span class="irs-grid-pol" style="left: 75%"></span>
                                            <span class="irs-grid-text js-grid-text-3" style="left: 75%; visibility: visible; margin-left: 0%;">3 749</span>
                                            <span class="irs-grid-pol small" style="left: 95%"></span>
                                            <span class="irs-grid-pol small" style="left: 90%"></span>
                                            <span class="irs-grid-pol small" style="left: 85%"></span>
                                            <span class="irs-grid-pol small" style="left: 80%"></span>
                                            <span class="irs-grid-pol" style="left: 100%"></span>
                                            <span class="irs-grid-text js-grid-text-4" style="left: 100%; margin-left: 0%;">4 999</span>
                                        </span>
                                        <span class="irs-bar irs-bar--single" style="left: 0px; width: 5.43794%;">
                                        </span>
                                        <span class="irs-shadow shadow-single" style="display: none;"></span>
                                        <span class="irs-handle single" style="left: 0%;">
                                            <i></i>
                                            <i></i>
                                            <i></i>
                                        </span> 
                                    </span>
                                    <input type="number" id="range_input" data-max="4999" class="irs-hidden-input" tabindex="-1" readonly="">
                                </div>
                            </div>
                            <div class="bonuses-val">
                                <input type="number" name="bonuses" id="bonuses" value="0">
                            </div>
                        </div>
                        <div class="tip">
                            Возможно списать до 20% от суммы заказа
                        </div>
                    </div>
                    <div class="right">
                        <div class="cart_sum_wrap">
                            <span class="cart_sum_title">Общая стоимость:</span>
                            <span class="cart_sum"><?= Service::formatPrice($cart->getCost()) ?></span>
                        </div>
                        <?php if ($cart->getItems()): ?>
                            <?php if ($cart->isAllowedCost() && $cart->isEnoughItemsQty()): ?>
                                <a href="<?= Url::to(['cart/checkout']) ?>" class="cart_checkout_btn common_btn">
                                    оформить заказ
                                </a>
                            <?php elseif (!$cart->isEnoughItemsQty()): ?>
                                <p>
                                    Минимально допустимое количество:
                                </p>
                                <?php foreach ($cart->getNotEnoughQtyItems() as $notEnoughQtyItem) : ?>
                                    <p>
                                        <?= $notEnoughQtyItem['title']; ?>
                                        <?= ($notEnoughQtyItem['weight']) ? ' - ' . $notEnoughQtyItem['weight'] : ''; ?>
                                        <?= ' : ' . $notEnoughQtyItem['min_qty']; ?> шт.
                                    </p>
                                <?php endforeach; ?>
                            <?php elseif (!$cart->isAllowedCost()): ?>
                                <p>
                                    Сумма заказа слишком низкая
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

