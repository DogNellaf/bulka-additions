<?php

use yii\helpers\Url;

/* @var $stories \common\entities\Stories[] */
/* @var $this yii\web\View */
?>

<div id="stories" class="stories page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Истории'); ?>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <ul class="stories_list list">
            <?php foreach ($stories as $story): ?>
                <?= $this->render('_story', [
                    'story' => $story,
                ]); ?>
            <?php endforeach; ?>
        </ul>
    </div>

</div>
