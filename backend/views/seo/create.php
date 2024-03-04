<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Seo */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
