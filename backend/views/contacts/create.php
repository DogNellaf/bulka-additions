<?php

/* @var $this yii\web\View */
/* @var $model common\entities\Contacts */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
