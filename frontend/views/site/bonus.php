<?php

/* @var $this yii\web\View */
/* @var $bonus \common\entities\Modules */
?>

<div id="policy" class="policy page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= $bonus->title; ?>
            </div>
        </div>
    </div>

    <div class="policy_block animated">
        <div class="wrapper">
            <div class="text text_1">
                <?= $bonus->html; ?>
            </div>
        </div>
    </div>

</div>
