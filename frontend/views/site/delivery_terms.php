<?php

/* @var $this yii\web\View */
/* @var $description \common\entities\Modules */
?>

<div id="delivery_terms" class="delivery_terms page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit($description->title); ?>
            </div>
        </div>
    </div>

    <div class="delivery_terms_block animated">
        <div class="wrapper">
            <div class="text text_1">
                <?= $description->html; ?>
            </div>
        </div>
    </div>

</div>
