<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Резерв', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserves-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
