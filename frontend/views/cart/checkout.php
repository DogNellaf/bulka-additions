<?php
use frontend\assets\CheckoutYandexMapAsset;
CheckoutYandexMapAsset::register($this);

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\components\Service;
use kartik\widgets\DateTimePicker;
use yii\widgets\MaskedInput;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View
 * @var $model \frontend\forms\OrderForm
 * @var $form yii\bootstrap\ActiveForm
 * @var $cart \common\models\Cart
 */

$this->title = 'Оформление заказа';

/* @var $user \common\entities\User */
$user = Yii::$app->user->identity;

//todo?
$isSumEnoughForDelivery = true;
?>

<div id="checkout" class="checkout page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
            </div>
        </div>
    </div>

    <div class="checkout_wrap">
        <div class="wrapper">
            <div class="checkout_block animated">
                <?php $form = ActiveForm::begin(['id' => 'checkout-form', 'options' => ['class' => 'checkout-form']]); ?>
                    <div class="checkout_step checkout_step_1" data-step="1">
                        <div class="check_delivery_method">
                            <div class="checkout-radio">
                                <?= $form->field($model, 'deliveryMethod')->radioList(Yii::$app->params['deliveryMethods'], [
                                    'item' => function ($index, $label, $name, $checked, $value) use ($model, $isSumEnoughForDelivery) {
                                        $hdn = '';
                                        if ($isSumEnoughForDelivery) {
                                            $ch = $value == 'delivery' ? 'checked' : '';
                                        } else {
                                            $ch = $value == 'pickup' ? 'checked' : '';
                                            $hdn = $value == 'delivery' ? 'hidden' : '';
                                        }
                                        return
                                            '<div class="checkout_delivery_input ' . $hdn . '">
                                     <input type="radio" class="delivery_radio" id="delivery_method-' . $value . '" name="' . $name . '" value="' . $value . '" ' . $ch . ' />
                                     <label for="delivery_method-' . $value . '" class="common_btn">' . $label . '</label>
                                 </div>';
                                    }
                                ])->label(false) ?>
                            </div>
                        </div>
                        <div class="checkout_step_btn_wrap">
                            <?php echo Html::button('Продолжить', ['class' => 'common_btn checkout_step_btn', 'data-next-step' => 2]) ?>
                            <?php echo Html::button('Редактировать', ['class' => 'common_btn checkout_step_edit_btn']) ?>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="check_info">
                        <div class="checkout_step checkout_step_2" data-step="2">
                            <?php if (!Yii::$app->user->isGuest && $user->userAddresses) : ?>
                                <div class="check_info_addresses">
                                    <div class="check_info_addresses_switcher">
                                        <label class="check_info_checkbox">
                                            <input type="radio" name="checkout_addresses_switch" checked="checked">
                                            <i></i><span>Использовать сохраненный адрес</span>
                                        </label>
                                        <label class="check_info_checkbox">
                                            <input type="radio" name="checkout_addresses_switch" class="clear_address">
                                            <i></i><span>Новый адрес</span>
                                        </label>
                                    </div>
                                    <div class="check_info_addresses_list">
                                        <?php $i = 1; ?>
                                        <?php foreach ($user->userAddresses as $address): ; ?>
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
                                            <div class="check_info_addresses_item <?= ($i == 1) ? 'active' : ''; ?>"
                                                 data-street="<?= $address->street; ?>"
                                                 data-house="<?= $address->house; ?>"
                                                 data-apartment="<?= $address->apartment; ?>"
                                                 data-floor="<?= $address->floor; ?>"
                                                 data-entrance="<?= $address->entrance; ?>"
                                                 data-intercom="<?= $address->intercom; ?>"
                                                 data-note="<?= $address->note; ?>"
                                            >
                                                <?= $address_title; ?>
                                            </div>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="check_top">
                                <div class="check_top_left">
                                    <div class="check_top_delivery_block">
                                        <div class="check_info_inputs">
                                            <?= $form->field($model, 'customer_name')->textInput(['maxlength' => 255]) ?>
                                            <?= $form->field($model, 'customer_phone')->widget(MaskedInput::class, [
                                                'mask' => '+7 (999) 999-99-99',
                                            ])->textInput() ?>
                                            <?= $form->field($model, 'customer_email')->textInput(['maxlength' => 255]) ?>

                                            <?= $form->field($model, 'street')->textInput(['maxlength' => 255, 'class' => 'dadata_address', 'autocomplete' => 'off']) ?>

                                            <div class="hidden">
                                                <?= $form->field($model, 'elementary_street')->hiddenInput() ?>
                                                <?= $form->field($model, 'elementary_house')->hiddenInput() ?>
                                            </div>

                                            <div class="form-group dadata_message_block">
                                                <div class="label"></div>
                                                <div class="dadata_message" data-coords=""></div>
                                            </div>

                                            <div class="hidden">
                                                <?= $form->field($model, 'house')->textInput(['maxlength' => 255]) ?>
                                            </div>
                                            <?= $form->field($model, 'apartment')->textInput(['maxlength' => 255]) ?>
                                            <?= $form->field($model, 'entrance')->textInput(['maxlength' => 255]) ?>
                                            <?= $form->field($model, 'intercom')->textInput(['maxlength' => 255]) ?>
                                            <?= $form->field($model, 'floor')->textInput(['maxlength' => 255]) ?>
                                        </div>
                                        <?php if (!Yii::$app->user->isGuest) : ?>
                                            <?= $form->field($model, 'save_address', [
                                                'options' => ['class' => 'check_info_checkbox_block'],
                                                'checkboxTemplate' => "{beginLabel}<div class='check_info_checkbox'>{input}<i></i><span>{labelTitle}</span></div>{endLabel}\n{hint}\n{error}",
                                            ])->checkbox(); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="check_top_pickup_block">
                                        <div class="check_info_inputs">
                                            <?= $form->field($model, 'customer_pickup_name')->textInput(['maxlength' => 255]) ?>
                                            <?= $form->field($model, 'customer_pickup_phone')->widget(MaskedInput::class, [
                                                'mask' => '+7 (999) 999-99-99',
                                            ])->textInput() ?>
                                            <?= $form->field($model, 'customer_pickup_email')->textInput(['maxlength' => 255]) ?>
                                        </div>
                                        <div class="delivery_pickup_block">
                                            <?php
                                            $delivery_pickup_points = ArrayHelper::map(\common\entities\Restaurants::find()->andWhere(['status' => 1])->orderBy('sort')->all(), 'id', 'title');
                                            $first_point = $delivery_pickup_points[array_key_first($delivery_pickup_points)];
                                            ?>
                                            <div class="delivery_pickup_point_input_wrap">
                                                <?= $form->field($model, 'delivery_pickup_point')->hiddenInput(['value' => array_key_first($delivery_pickup_points)]) ?>
                                            </div>
                                            <div class="select_wrap">
                                                <div class="select_cur">
                                                    <div class="select_cur_text">
                                                        <?= $first_point; ?>
                                                    </div>
                                                    <div class="select_arr">
                                                        <div class="select_arr_ins">
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="select_list">
                                                    <?php foreach ($delivery_pickup_points as $key => $delivery_pickup_point) : ?>
                                                        <li class="select_opt" data-val="<?= $key; ?>">
                                                            <?= $delivery_pickup_point; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="check_top_right">
                                    <div class="note">
                                        <div class="check_info_subtitle">
                                            Комментарий к заказу
                                        </div>
                                        <?= $form->field($model, 'note')->textarea(['rows' => 7])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout_map_block">
                                <div class="checkout_map_btn_block">
                                    <button type="button" class="checkout_map_btn common_btn">
                                        Показать карту
                                    </button>
                                </div>
                                <div class="hidden">
                                    <?php
                                    $map_json = \common\entities\Modules::findOne(13)->html;
                                    ?>
                                    <textarea class="map_json"><?= $map_json; ?></textarea>
                                </div>
                                <div class="checkout_map_wrap">
                                    <div class="check_info_subtitle">
                                        Зоны доставки
                                    </div>
                                    <div class="checkout_map">
                                        <div id="checkout_map_place" class="checkout_map_place"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout_step_btn_wrap">
                                <?php echo Html::button('Продолжить', ['class' => 'common_btn checkout_step_btn', 'data-next-step' => 3]) ?>
                                <?php echo Html::button('Редактировать', ['class' => 'common_btn checkout_step_edit_btn']) ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="checkout_step checkout_step_3" data-step="3">
                            <div class="delivery_datetime_block">
                                <div class="check_info_subtitle">
                                    Выберите удобное время доставки
                                </div>
                                <div class="delivery_datetime">
                                    <div class="delivery_date">
                                        <?= $form->field($model, 'delivery_date')->widget(
                                            DateTimePicker::class, [
                                            'language' => 'ru',
                                            'options' => ['class' => 'form-control dtInput', 'autocomplete' => 'off', 'readonly' => true],
                                            'type' => DateTimePicker::TYPE_INPUT,
                                            'pluginOptions' => [
                                                'startDate' => Yii::$app->formatter->asDatetime((time() + $cart->getMinDeliveryDays() * 24 * 3600), 'dd.MM.yyyy'),
                                                'todayHighlight' => false,
                                                'todayBtn' => true,
                                                'autoclose' => true,
                                                'format' => 'dd.mm.yyyy',
                                                'minView' => 'month',
                                            ]
                                        ]); ?>
                                    </div>
                                    <div class="delivery_time_wrap">
                                        <div class="delivery_time" data-href="<?= Url::to(['cart/get-delivery-time-intervals']); ?>">
                                            <?= $this->render('_delivery_time_intervals', [
                                                'delivery_self' => ($isSumEnoughForDelivery) ? null : 1,
                                            ]); ?>
                                        </div>
                                        <div class="delivery_time_error_block">
                                            Выберите время
                                        </div>
                                        <div class="delivery_time_input_wrap">
                                            <?= $form->field($model, 'delivery_time')->hiddenInput(['value' => 0, 'data-cost' => 0])->label(false) ?>
                                        </div>
                                    </div>
                                </div>
                                <!--todo-->
                                <div class="delivery_quickly hidden">
                                    <?= $form->field($model, 'delivery_quickly', [
                                        'options' => ['class' => 'check_info_checkbox_block'],
                                        'checkboxTemplate' => "{beginLabel}<div class='check_info_checkbox'>{input}<i></i><span>{labelTitle}</span></div>{endLabel}\n{hint}\n{error}",
                                    ])->checkbox(); ?>
                                </div>
                            </div>
                            <div class="checkout_step_btn_wrap">
                                <?php echo Html::button('Продолжить', ['class' => 'common_btn checkout_step_btn', 'data-next-step' => 4]) ?>
                                <?php echo Html::button('Редактировать', ['class' => 'common_btn checkout_step_edit_btn']) ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="checkout_step checkout_step_4" data-step="4">
                            <div class="check_pay_method">
                                <div class="checkout_subtitle">
                                    Способ оплаты
                                </div>
                                <div class="check_pay_method_ins">
                                    <div class="checkout-radio">
                                        <?
                                        $payMethods = Yii::$app->params['payMethods'];
                                        if (Yii::$app->user->isGuest || !$user->isBusinessClient()) {
                                            unset($payMethods['contract']);
                                        } else {
                                            unset($payMethods['online']);
                                        }
                                        ?>
                                        <?= $form->field($model, 'payMethod')->radioList($payMethods, [
                                            'item' => function ($index, $label, $name, $checked, $value) use ($model, $isSumEnoughForDelivery) {
                                                $ch = '';
                                                if (in_array($value, ['online', 'contract'])) {
                                                    $ch = 'checked';
                                                }
                                                //$ch = $value == 'online' ? 'checked' : '';
                                                $deliveryPayMethod = '';
                                                $hiddenPayMethod = '';
                                                if ($value == 'card' || $value == 'cash') {
                                                    $deliveryPayMethod = 'delivery_pay_method';
                                                    if ($isSumEnoughForDelivery) {
                                                        $hiddenPayMethod = '';
                                                    } else {
                                                        $hiddenPayMethod = 'hidden_method';
                                                    }
                                                } elseif ($value == 'card_on_self' || $value == 'cash_on_self') {
                                                    $deliveryPayMethod = 'self_pay_method';
                                                    if ($isSumEnoughForDelivery) {
                                                        $hiddenPayMethod = 'hidden_method';
                                                    } else {
                                                        $hiddenPayMethod = '';
                                                    }
                                                }
                                                return
                                                    '<div class="checkout_pay_input ' . $deliveryPayMethod . ' ' . $hiddenPayMethod . '">
                                     <input type="radio" class="pay_radio" id="pay_method-' . $value . '" name="' . $name . '" value="' . $value . '" ' . $ch . ' />
                                     <label for="pay_method-' . $value . '"><i></i><span>' . $label . '</span></label>
                                 </div>';
                                            }
                                        ])->label(false) ?>
                                    </div>
                                    <div class="checkout_logos">
                                        <img src="/images/pay_logos/visa_inc_logo.png" alt="">
                                        <img src="/images/pay_logos/mastercard-logo.png" alt="">
                                        <img src="/images/pay_logos/jcb_logo.png" alt="">
                                        <img src="/images/pay_logos/apple_pay_logo.png" alt="">
                                        <img src="/images/pay_logos/google_pay_logo.png" alt="">
                                    </div>
                                </div>
                                <div class="checkout_pay_input_today_info">
                                    При оформлении заказа на сегодня доступна только онлайн оплата. Пожалуйста, оплатите картой на сайте.
                                </div>
                            </div>
                            <div class="checkout_step_btn_wrap">
                                <?php echo Html::button('Продолжить', ['class' => 'common_btn checkout_step_btn', 'data-next-step' => 5]) ?>
                                <?php echo Html::button('Редактировать', ['class' => 'common_btn checkout_step_edit_btn']) ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="checkout_step checkout_step_5" data-step="5">
                            <div class="checkout_cart">
                                <div class="checkout_subtitle">
                                    Ваш заказ
                                </div>
                                <div class="checkout_cart_block">
                                    <div class="checkout_cart_row_header checkout_cart_row">
                                        <div class="title">
                                            товар
                                        </div>
                                        <div class="count_col">
                                            количество
                                        </div>
                                        <div class="price">
                                            стоимость
                                        </div>
                                    </div>
                                    <div class="checkout_cart_items">
                                        <?php foreach ($cart->getItems() as $item):
                                            /* @var  $item \common\models\CartItem */
                                            $product = $item->getProduct();
                                            $url = Url::to(['catalog/product', 'slug' => $product->slug]);
                                            ?>
                                            <div class="checkout_cart_item checkout_cart_row">
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
                                                <div class="count_col">
                                                    <div class="counts">
                                                        <span class="quantity count"><?= $item->getQuantity() ?></span>
                                                    </div>
                                                </div>
                                                <div class="price">
                                                    <?= Service::formatPrice($item->getCost()) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="checkout_cart_bottom">
                                        <div class="left">
                                            <div class="agree_link_wrap">
                                                <?php $policyUrl = Url::to(['/site/policy']); ?>
                                                <?php $contractOfferUrl = Url::to(['/site/policy']); ?>
                                                <?= $form->field($model, 'data_collection_checkbox', [
                                                    'options' => ['class' => 'form-group data-checkbox'],
                                                    'checkboxTemplate' => "<div class='agree_link'>
                                                                        {beginLabel}{input}<i></i>{endLabel}
                                                                        <span>Согласие на обработку 
                                                                        <a href='{$policyUrl}' class='agree_link_policy policy_popup_btn lined black' target='_blank'>
                                                                        персональных данных
                                                                        </a>
                                                                        и с условиями 
                                                                        <a href='{$contractOfferUrl}' class='agree_link_policy contract_offer_popup_btn lined black' target='_blank'>
                                                                        договора-оферты
                                                                        </a>
                                                                        </span>
                                                                        </div>
                                                                        \n{hint}\n{error}"
                                                ])->checkbox(); ?>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="checkout_cart_sum_wrap">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            Общая стоимость:
                                                        </td>
                                                        <td class="checkout_cost" data-cost="<?= $cart->getCost() + $cart->getBonuses(); ?>">
                                                            <?= Service::formatPrice($cart->getCost()) ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Доставка
                                                        </td>
                                                        <td>
                                                            <div class="hidden">
                                                                <?= $form->field($model, 'delivery_cost')->hiddenInput(['value' => null]) ?>
                                                            </div>
                                                            <span class="checkout_delivery_cost" data-cost="0">0 ₽</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            СПИШЕТСЯ БАЛЛОВ
                                                        </td>
                                                        <td>
                                                            <div class="hidden">
                                                                <?= Service::formatPrice($cart->getBonuses()) ?>
                                                            </div>
                                                            <span class="checkout_bonuses" data-cost="-<?= Service::formatPrice($cart->getBonuses()) ?>"><span class="ico icon-coins"></span> <?= Service::formatPrice($cart->getBonuses()) ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Итого:
                                                        </td>
                                                        <td class="checkout_total_cost">
                                                            <?= Service::formatPrice($cart->getCost()) ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="checkout_cart_sum_btns">
                                                <?php echo Html::submitButton('Заказать', ['class' => 'submit common_btn checkout_submit_btn']) ?>
                                                <div class="clr"></div>
                                                <a href="<?= Url::to(['/cart/index']) ?>" class="checkout_cart_return_btn common_btn revert">
                                                    Редактировать заказ
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
