<?php
/* @var $this yii\web\View */
/* @var $model common\entities\RestaurantMenus */
/* @var $category array */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' =>  $category['title'], 'url' => ['index', 'id' => $category['id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-menus-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
