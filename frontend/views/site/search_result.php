<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this */
/* @var $result \yii\data\ActiveDataProvider */
/* @var $param string */

/**
 * @param $string string
 * @param $param string
 * @return string
 */
function strReplace($string, $param)
{
    return str_replace($param, '<span class="highlight">' . $param . '</span>', $string);
}
?>

<div id="search_result" class="search_result page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit('Поиск'); ?>
            </div>
        </div>
    </div>


    <div class="search_result_top_block animated">
        <div class="wrapper">
            <div class="search_result_top">
                <form class="search_result_form">
                    <input type="text" name="str" placeholder="Поиск" value="<?= ($param) ? $param : ''; ?>">
                    <div class="submit">
                        <button type="submit">
                            <i class="icon-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="search_result_list_wrap catalog_list_wrap">
            <?php if (Yii::$app->request->get('str') && Yii::$app->request->get('str') !== ''): ?>
                <?php if (empty($result)): ?>
                    <p>Ничего не найдено</p>
                <?php endif; ?>

                <?php if (!empty($result)): ; ?>
                    <?= ListView::widget([
                        'dataProvider' => $result,
                        'options' => ['class' => 'search_result_list product_list'],
                        'itemOptions' => ['tag' => false,],
                        'summary' => '',
                        'itemView' => function ($model) {
                            return $this->render('/catalog/_product', [
                                'product' => $model,
                            ]);
                        },
                    ]) ?>

                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

</div>
