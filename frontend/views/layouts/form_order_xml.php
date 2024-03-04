<?php
/* @var $order \common\entities\Orders */

$delivery_pickup_point_title = null;
if ($order->delivery_pickup_point) {
    $delivery_pickup_point_title = \common\entities\Restaurants::findOne($order->delivery_pickup_point)->title;
}
$payment_methods = [
    'online' => 'commerce_rbspayment|commerce_payment_commerce_rbspayment',
    'card' => 'commerce_ccod|commerce_payment_commerce_ccod',
    'cash' => 'commerce_cod|commerce_payment_commerce_cod',
    'cash_on_self' => 'commerce_poc|commerce_payment_commerce_poc',
    'card_on_self' => 'commerce_cc|commerce_payment_commerce_cc',
    //???
    'paypal' => 'paypal_wps|commerce_payment_paypal_wps',
    'contract' => 'commerce_contract|commerce_payment_commerce_contract',
];

/* @var $user \common\entities\User */
$user = Yii::$app->user->identity;
$user_id = null;
$user_type = 'personal';
if (!Yii::$app->user->isGuest) {
    $user_id = $user->id;
    if ($user->isBusinessClient()) {
        $user_type = 'business';
    }
}

?>
<bulka_order>
    <order_number><?= $order->id; ?></order_number>
    <transaction_date><?= Yii::$app->formatter->asDate($order->created_at, 'dd/MM/yy'); ?></transaction_date>
    <order_data>
        <collection_point><?= $delivery_pickup_point_title; ?></collection_point>
        <name></name>
        <phone></phone>
        <comment><?= $order->note; ?></comment>
        <payment_method><?= $payment_methods[$order->pay_method]; ?></payment_method>
    </order_data>
    <order_total><?= $order->cost + $order->delivery_cost; ?></order_total>
    <email><?= $order->email; ?></email>
    <user_id><?= $user_id; ?></user_id>
    <inn_number><?= $user->inn; ?></inn_number>
    <?php
    /*
    ?>
    <user_kpp><?= $user->kpp; ?></user_kpp>
    <?php
    */
    ?>
    <?php if ($user_type == 'business' && $user->company_name) : ?>
        <company_name><?= $user->company_name; ?></company_name>
    <?php endif; ?>
    <account_type><?= $user_type; ?></account_type>
    <shipping>
        <service></service>
        <price><?= $order->delivery_cost; ?></price>
    </shipping>
    <delivery_time><?= $order->delivery_date . ' ' . $order->delivery_time; ?></delivery_time>
    <?php
    /*
    ?>
    <billing_details>
        <?php
        */
    ?>
    <shipping_details>
        <name><?= $order->name; ?></name>
        <billing_street><?= $order->street; ?></billing_street>
        <billing_city>Москва</billing_city>
        <country>RU</country>
        <phone><?= $order->phone; ?></phone>
        <comment><?= $order->note; ?></comment>
        <house><?= $order->house; ?></house>
        <apartment><?= $order->apartment; ?></apartment>
        <entrance><?= $order->entrance; ?></entrance>
        <intercom><?= $order->intercom; ?></intercom>
        <floor><?= $order->floor; ?></floor>
        <?php
        /*
        ?>
        <entrance_code/> - домофон
            <?php
            */
        ?>
    </shipping_details>
    <line_items>
        <?php $i = 0; ?>
        <?php foreach ($order->orderItems as $orderItem) : ?>
            <item<?= $i; ?>>
                <sku><?= $orderItem->product->sku; ?></sku>
                <ID_1C><?= $orderItem->getWeight()->id_1c; ?></ID_1C>
                <?php
                $options = '';
                if ($orderItem->options) {
                    foreach ($orderItem->options as $option) {
                        $options .= $option->title . ' ';
                    }
                }
                ?>
                <options><?= $options; ?></options>
                <quantity><?= $orderItem->qty_item; ?>.00</quantity>
                <unit_price><?= $orderItem->price_item; ?></unit_price>
            </item<?= $i; ?>>
            <?php $i++; ?>
        <?php endforeach; ?>
    </line_items>
</bulka_order>
