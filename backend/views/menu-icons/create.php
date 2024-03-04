<?php
/* @var $this yii\web\View */
/* @var $model \common\entities\MenuIcons */


$this->title = 'Добавить';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
