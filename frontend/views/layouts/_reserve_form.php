<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use frontend\forms\ReserveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;

$model = new ReserveForm();

/* @var $model \frontend\forms\ReserveForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form">
    <?php $form = ActiveForm::begin(['action' => ['site/get-reserve']]); ?>

    <div class="form_input_wrap">
        <div class="form_input_block">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="form_input_block">
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
                'mask' => '+9 (999) 999-99-99',
            ])->textInput(); ?>
        </div>
        <div class="form_input_block reserveform-restaurant-id-block">
            <?php
            $restaurants = ArrayHelper::map(\common\entities\Restaurants::find()->andWhere(['status' => 1])->orderBy('sort')->all(), 'id', 'title');
            ?>
            <?= $form->field($model, 'restaurant_id')->hiddenInput(['value' => array_key_first($restaurants)]); ?>
            <div class="select_wrap">
                <div class="select_cur">
                    <div class="select_cur_text">
                        <?= $restaurants[array_key_first($restaurants)]; ?>
                    </div>
                    <div class="select_arr">
                        <div class="select_arr_ins">
                        </div>
                    </div>
                </div>
                <ul class="select_list">
                    <?php foreach ($restaurants as $key => $restaurant) : ?>
                        <li class="select_opt" data-id="<?= $key; ?>">
                            <?= $restaurant; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="date_persons_wrap">
            <div class="form_input_block">
                <?= $form->field($model, 'date')->widget(
                    DateTimePicker::class, [
                    'language' => 'ru',
                    'options' => ['class' => 'form-control dtInput'],
                    'type' => DateTimePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'startDate' => Yii::$app->formatter->asDatetime((time() + 30 * 60), 'dd-MM-yyyy H:m'),
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy H:ii'
                    ]
                ]); ?>

            </div>
            <div class="form_input_block">
                <?= $form->field($model, 'persons')->textInput(['maxlength' => true]); ?>
            </div>
        </div>
        <div class="form_input_block">
            <?= $form->field($model, 'notes')->textarea(['rows' => 9]); ?>
        </div>

        <?php //if (Yii::$app->user->isGuest): ; ?>
        <div class="form_input_block">
            <div class="input_label">
                <?= $model->getAttributeLabel('verifyCode'); ?>
            </div>
            <div class="captcha">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'captchaAction' => 'site/captcha',
                    'template' => '<div class="captchaBlock"><div class="captchaInput">{input}</div><div class="captchaImage">{image}</div></div>',
                    'options' => ['placeholder' => $model->getAttributeLabel('verifyCode').' *'],
                ])->label(false) ?>
            </div>
        </div>
        <?php //endif; ?>

    </div>
    <div class="form_bottom_block">
        <div class="false_label">
        </div>
        <div class="right_block">
            <div class="form_input_block submit_block">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'common_btn']) ?>
            </div>
            <div class="agree_link_wrap">
                <?php $policyUrl = Url::to(['/site/policy']); ?>
                <?= $form->field($model, 'data_collection_checkbox', [
                    'options' => ['class' => 'form-group data-checkbox'],
                    'checkboxTemplate' => "<div class='agree_link'>
                                            {beginLabel}{input}<i></i>{endLabel}
                                            <span>Согласие на обработку 
                                            <a href='{$policyUrl}' class='agree_link_policy policy_popup_btn lined black' target='_blank'>
                                            персональных данных
                                            </a>
                                            </span>
                                            </div>
                                            \n{hint}\n{error}"
                ])->checkbox(); ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

