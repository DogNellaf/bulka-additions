<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\forms\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login page padded padded_bottom">
    <div class="wrapper">
        <div class="form">

            <div class="page_header">
                <div class="wrapper">
                    <div class="title title_1 font_2">
                        <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
                    </div>
                </div>
            </div>

            <p><?= Yii::t('app', 'Заполните следующие поля для входа'); ?>:</p>
            <br>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <div class="form_input_wrap">

                <div class="form_input_block">
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('email') . ' *'])->label(false); ?>
                </div>
                <div class="form_input_block">
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password') . ' *'])->label(false); ?>
                </div>
                <div class="form_agree">
                    <div class="check_block">
                        <?= $form->field($model, 'rememberMe',
                            [
                                'options' => ['class' => 'form-group data-checkbox'],
                                'checkboxTemplate' => '{beginLabel}{input}<div class="check_pseudo"></div><span>Запомнить меня</span>{endLabel}{error}{hint}',
                            ])->checkbox()
                            ->label('Запомнить меня'); ?>
                    </div>
                </div>

                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('app', 'Если вы забыли свой пароль, вы можете '); ?>
                    <?= Html::a(Yii::t('app', 'сбросить его'), ['account/request-password-reset']) ?>.
                </div>
                <div style="color:#999;margin:1em 0">
                    <?= Yii::t('app', 'Если у вас нет аккаунта, '); ?>
                    <?= Html::a(Yii::t('app', 'зарегистрируйтесь'), ['account/signup']) ?>.
                </div>

                <div class="form_input_wrap">
                    <div class="form_input_block submit_block">
                        <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'common_btn black', 'name' => 'login-button']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
