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

<script src="/js/vendor/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="/js/confirm-script.js" type="text/javascript"></script>

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
            <form action="<?= Url::to(['loyalty/confirm']); ?>" method="POST">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <div class="form_input_wrap">
                    <div class="code-form">
                        <p>
                            Введите код, отправленный на указанный вами номер телефона
                        </p>
                        <div class="form_input_block code-inputs-row">
                            <input type="number" id="num_1" name="num1">
                            <input type="number" id="num_2" name="num2">
                            <input type="number" id="num_3" name="num3">
                            <!-- <input type="number" id="num_4"> -->
                        </div>
                        <div class="get-new-code timer-active">
                            <a href="<?= Url::to(['loyalty/register']); ?>" class="common_btn get-new-code-btn">отправить код повторно</a>
                            <div class="code-timer-block">
                                Повторная отправка возможна через <span class="code-timer" data-start="60">1</span> сек.
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