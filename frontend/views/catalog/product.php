<?php

use frontend\components\Service;
use yii\helpers\Url;

/* @var $product \common\entities\Products */
/* // @var $relProducts \common\entities\Products[] */
?>

<div id="product" class="product page padded padded_bottom">
    <div class="wrapper">
        <div class="product_ins">
            <div class="product_ins_top">
                <div class="img img_hover_item">
                    <img src="<?= $product->image; ?>" alt="">
                </div>
                <div class="right">
                    <div class="title animated">
                        <?= $product->title; ?>
                    </div>
                    <div class="price productPrice animated" data-base-price="<?= $product->getProductBasePrice(); ?>">
                        <?= \frontend\components\Service::formatPrice($product->getProductBasePrice()); ?>
                    </div>
                    <div class="weights animated">
                        <?php $i = 1; ?>
                        <?php foreach ($product->productWeights as $weight) : ?>
                            <label class="weight productWeight">
                                <input type="radio"
                                       name="product-weight"
                                       value="<?= $weight->id; ?>"
                                       data-price="<?= $weight->getPrice(); ?>"
                                       data-balance="<?= $weight->balance; ?>"
                                        <?= ($i == 1) ? 'checked' : ''; ?>
                                >
                                <span class="weight_title"><?= $weight->title; ?></span>
                            </label>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($product->productOptions) : ?>
                        <div class="options_block animated">
                            <div class="options_header">
                                Дополнительные опции
                            </div>
                            <div class="options">
                                <?php foreach ($product->productOptions as $option) : ?>
                                    <label class="option productOption">
                                        <input type="checkbox" name="product-option" value="<?= $option->id; ?>" data-price="<?= $option->getPrice(); ?>">
                                        <i></i>
                                        <span class="option_title"><?= $option->title; ?></span>
                                        <span class="option_price">- <?= Service::formatPrice($option->getPrice()) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="clr"></div>
                    <div class="cart_btn_block_wrap">
                        <?php $firstProductWeight = $product->productWeights[array_key_first($product->productWeights)]; ?>
                        <div class="cart_btn_block animated is_balance <?= ($firstProductWeight->balance === 0) ? 'hidden' : ''; ?>">
                            <a href="<?= Url::to(['cart/plus', 'id' => $product->id]); ?>"
                               class="cart_btn common_btn border_radius cartButton cartButtonWithOptions">
                                <span>в корзину</span><span class="quantity">1</span>
                            </a>
                            <a href="<?= Url::to(['cart/minus', 'id' => $product->id]); ?>"
                               data-href="<?= Url::to(['cart/minus', 'id' => $product->id]); ?>"
                               class="cart_btn_sign minus cartButton cartButtonWithOptions">
                                <span>-</span>
                            </a>
                            <a href="<?= Url::to(['cart/plus', 'id' => $product->id]); ?>"
                               data-href="<?= Url::to(['cart/plus', 'id' => $product->id]); ?>"
                               class="cart_btn_sign plus cartButton cartButtonWithOptions">
                                <span>+</span>
                            </a>
                        </div>
                        <div class="cart_btn_block animated no_balance <?= ($firstProductWeight->balance !== 0) ? 'hidden' : ''; ?>">
                            <button class="cart_btn common_btn border_radius">
                                <span>Нет в наличии</span>
                            </button>
                        </div>
                    </div>
                    <div class="product_min_qty_block animated">
                        <?php $i = 1; ?>
                        <?php foreach ($product->productWeights as $weight) : ?>
                            <?php if ($weight->min_quantity) : ?>
                                <div class="product_min_qty <?= ($i == 1) ? 'active' : ''; ?>" data-weight="<?= $weight->id; ?>">
                                    Минимум: <?= $weight->min_quantity; ?> шт.
                                </div>
                            <?php endif; ?>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="clr"></div>
                    <div class="product_add_to_fav animated">
                        <?php if ($product->isFavorite(Yii::$app->user->id)) {?>
                            <a data-id='<?=$product->id?>' class="fav_btn favDel">
                                <i class="icon-heart"></i>
                                <span><?= Yii::t('app', 'В избранном');?></span>
                            </a>
                        <?php }else{?>
                            <a data-id='<?=$product->id?>' class="fav_btn favAdd">
                                <i class="icon-heart-o"></i>
                                <span><?= Yii::t('app', 'Добавить в избранное');?></span>
                            </a>
                        <?php } ?>
                    </div>
                    <!--todo need?-->
                    <!--
                    <div class="product_weight animated">
                        <span class="product_weight_title">Вес: </span><span class="product_weight_val">150 г</span>
                    </div>
                    -->
                    <div class="description animated">
                        <?= $product->description; ?>
                    </div>
                    <div class="product_energy_val_block animated">
                        <div class="product_energy_val_title">
                            Пищевая и энергетическая ценность на 100 г:
                        </div>
                        <table class="product_energy_val_table">
                            <tr class="product_energy_val_th">
                                <td>
                                    Ккал
                                </td>
                                <td>
                                    Белки
                                </td>
                                <td>
                                    Жиры
                                </td>
                                <td>
                                    Углеводы
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?= $product->kcal; ?>
                                </td>
                                <td>
                                    <?= $product->proteins; ?>
                                </td>
                                <td>
                                    <?= $product->fats; ?>
                                </td>
                                <td>
                                    <?= $product->carbohydrates; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="back_link animated">
                        <a href="<?= Url::to(['catalog/index']); ?>" class="lined black">
                            Назад
                        </a>
                    </div>
                </div>
            </div>
            <?php if ($product->relProducts): ?>
                <div class="product_rel_block">
                    <div class="product_rel_title animated">
                        Попробуйте также
                    </div>
                    <div class="product_rel_list product_list">
                        <?php foreach ($product->relProducts as $relProduct): ?>
                            <?= $this->render('/catalog/_product', [
                                'product' => $relProduct,
                            ]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
