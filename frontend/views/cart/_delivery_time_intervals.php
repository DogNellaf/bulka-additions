<?php
/* @var $date string|null */
/* @var $delivery_self int|null */
/* @var $shape_id int|null */
/* @var $inner_mkad int|null */
/* @var $cart \common\models\Cart */
/* @var $cost_module \common\entities\Modules */

$cost_module = \common\entities\Modules::findOne(9);
$cart = Yii::$container->get('common\models\Cart');
$cost = $cart->getCost();
$minDeliveryDays = $cart->getMinDeliveryDays();

$freeDelivery = false;
$deliveryTimeModelId = null;

if ($delivery_self) {
    $deliveryTimeModelId = 1;
} elseif ($shape_id) {
    $shape = \common\entities\DeliveryTimes::findOne(['zone_id' => $shape_id]);
    if ($shape && $shape->deliveryTimeIntervals) {
        $deliveryTimeModelId = $shape->id;
    } elseif ($inner_mkad) {
        $deliveryTimeModelId = 2;
    } else {
        $deliveryTimeModelId = 3;
    }
} elseif ($inner_mkad) {
    $deliveryTimeModelId = 2;
} else {
    $deliveryTimeModelId = 3;
}

if ($inner_mkad) {
    if ($cost > $cost_module->min_free_delivery_sum) {
        $freeDelivery = true;
    }
} else {
    if ($cost > $cost_module->min_free_delivery_sum_outer_mkad) {
        $freeDelivery = true;
    }
}

$deliveryTimeIntervals = [];
/* @var $deliveryTimeModel \common\entities\DeliveryTimes */
$deliveryTimeModel = \common\entities\DeliveryTimes::findOne($deliveryTimeModelId);
foreach ($deliveryTimeModel->deliveryTimeIntervals as $deliveryTimeInterval) {
    $deliveryTimeIntervals[] = [
        'start' => $deliveryTimeInterval->start,
        'end' => $deliveryTimeInterval->end,
        'cost' => ($deliveryTimeInterval->cost) ? : 0,
    ];
}
if ($date) {
    $today = Yii::$app->formatter->asDatetime((time()), 'dd.MM.yyyy');
    if ($date == $today) {
        foreach ($deliveryTimeIntervals as $key => $interval) {
            $intervalEndTime = \DateTime::createFromFormat('H:i', $interval['end']);
            if ($intervalEndTime && ($intervalEndTime->getTimestamp() < (time() + 2 * 3600))) {
                unset($deliveryTimeIntervals[$key]);
            }
        }
    }
}

//sort($deliveryTimeIntervals);
?>
<div>
    <div class="ajax-cont">
        <div class="select_wrap">
            <div class="select_cur">
                <div class="select_cur_text">
                    Выбрать
                </div>
                <div class="select_arr">
                    <div class="select_arr_ins">
                    </div>
                </div>
            </div>
            <ul class="select_list">
                <?php foreach ($deliveryTimeIntervals as $key => $interval) : ?>
                    <?php
                    $intervalTitle = $interval['start'] . ' - ' . $interval['end'];
                    if ($freeDelivery) {
                        $cost = 0;
                    } else {
                        $cost = $interval['cost'];
                    }
                    ?>
                    <li class="select_opt" data-interval="<?= $intervalTitle; ?>" data-cost="<?= $cost; ?>">
                        <?= $intervalTitle; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
