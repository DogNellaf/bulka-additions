<?php
/* @var $this yii\web\View */
/* @var $model common\entities\BonusDocs */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="galleries-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


