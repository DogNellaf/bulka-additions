<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use frontend\forms\CallbackForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$model = new CallbackForm();

/* @var $model \frontend\forms\CallbackForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form">
    <?php $form = ActiveForm::begin(['action' => ['site/get-callback']]); ?>

    <div class="form_input_wrap">
        <div class="form_input_block">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="form_input_block">
            <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
                'mask' => '+9 (999) 999-99-99',
            ])->textInput(); ?>
        </div>
        <div class="form_input_block">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="form_input_block">
            <?= $form->field($model, 'notes')->textarea(['rows' => 9]); ?>
        </div>

        <?php //if (Yii::$app->user->isGuest): ; ?>
        <div class="form_input_block captcha">
            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'captchaAction' => 'site/captcha',
                'template' => '<div class="captchaBlock"><div class="captchaInput">{input}</div><div class="captchaImage">{image}</div></div>',
                'options' => ['placeholder' => $model->getAttributeLabel('verifyCode').' *'],
            ]) ?>
        </div>
        <?php //endif; ?>

    </div>
    <div class="form_bottom_block">
        <div class="false_label">
        </div>
        <div class="right_block">
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
            <div class="form_input_block submit_block">
                <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'common_btn']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

