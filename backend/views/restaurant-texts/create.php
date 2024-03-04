<?php
/* @var $this yii\web\View */
/* @var $model common\entities\RestaurantTexts */
/* @var $category array */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' =>  $category['title'], 'url' => ['index', 'id' => $category['id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-texts-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
