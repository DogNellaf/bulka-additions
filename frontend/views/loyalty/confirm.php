<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\SignupForm */

$this->title = 'Регистрация в бонусной системе';
$this->params['breadcrumbs'][] = $this->title;
?>

<script src="/js/script.js" type="text/javascript"></script>

<div class="site-login page padded padded_bottom">
    <div class="wrapper">
        <div class="form phone-login-form">
            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        Вход
                    </div>
                </div>
            </div>
            <form action="">
                <div class="form_input_wrap">
                    <div class="code-form">
                        <p>
                            Введите код, отправленный на указанный вами номер телефона
                        </p>
                        <div class="form_input_block code-inputs-row">
                            <input type="number" id="num_1">
                            <input type="number" id="num_2">
                            <input type="number" id="num_3">
                            <input type="number" id="num_4">
                        </div>
                        <div class="get-new-code">
                            <a href="<?= Url::to(['loyalty/confirm']); ?>" class="common_btn get-new-code-btn">отправить код повторно</a>
                            <div class="code-timer-block">
                                Повторная отправка возможна через <span class="code-timer" data-start="5">1</span> сек.
                            </div>
                        </div>
                        <div class="form_input_wrap get-new-code-confirm">
                            <div class="form_input_block submit_block">
                                <button type="submit" class="common_btn">
                                    войти
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 
<div class="site-signup page padded padded_bottom">
    <div class="wrapper">
        <div class="form">

            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
                    </div>
                </div>
            </div>

            <p>Заполните следующие поля для регистрации:</p>
            <br>

            <?php $form = ActiveForm::begin(['id' => 'form-loyalty-register']); ?>

            <div class="form_input_wrap">

                <div class="form_input_block">
                    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
	                    'mask' => '+7 (999) 999 99 99',
                    ])->label(false); ?>
                </div>
                <div class="form_input_block submit_block">
                    <?= Html::submitButton(Yii::t('app', 'Зарегистрироваться'), ['class' => 'common_btn black', 'name' => 'signup-button']) ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div> -->