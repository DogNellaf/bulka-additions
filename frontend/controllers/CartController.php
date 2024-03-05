<?php

namespace frontend\controllers;

use common\entities\Orders;
use common\models\SberBank;
use common\models\smsru\SMSRU;
use frontend\forms\OrderForm;
use Yii;
use yii\base\Module;
use yii\web\NotFoundHttpException;
use common\models\Cart;
use common\models\CartItem;
use common\entities\Products;
use frontend\components\FrontendController;
use yii\web\View;

class CartController extends FrontendController
{
    /* @var $cart Cart */
    private $cart;

    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cart = Yii::$container->get('common\models\Cart');
    }

    public function actionIndex()
    {
        if (!$this->cart->getItems()) {
            Yii::$app->session->setFlash('error', 'Корзина пуста.');
            return $this->redirect(['catalog/index']);
        }
        return $this->render('index', [
            'cart' => $this->cart,
        ]);
    }

    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        $quantity = $post['quantity-input'] ?: $post['quantity'];
        $menu_item = Products::findOne($post['id']);
        $cart_item = new CartItem($menu_item, $quantity, null, null);
        $this->cart->add($cart_item);
        Yii::$app->session->setFlash('success', 'Добавлено в корзину.');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPlus($id, $weight = null, $options = '')
    {
        if (!$product = Products::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $cart_item = new CartItem($product, 1, $weight, $options);
        $this->cart->add($cart_item);
        $data = null;
        $qty = null;
        if ($item = $this->cart->getItem($id, $weight, $options)) {
            $data = $item->getProduct();
            $qty = $item->getQuantity();
        }
        return $this->renderAjax('ajax_cart', [
            'item' => $item,
            'data' => $data,
            'quantity' => $qty,
            'message' => 'Добавлено в корзину:'
        ]);
    }

    public function actionMinus($id, $weight = null, $options = '')
    {
        if (!$product = Products::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        if (!$this->cart->getItem($id, $weight, $options)) {
            return false;
        }
        $cart_item = new CartItem($product, -1, $weight, $options);
        $this->cart->add($cart_item);
        if (!$item = $this->cart->getItem($id, $weight, $options)) {
            return $this->renderAjax('ajax_cart', [
                'data' => $product,
                'quantity' => null,
                'message' => 'Удалено из корзины:'
            ]);
        }

        $data = $item->getProduct();
        $qty = $item->getQuantity();

        return $this->renderAjax('ajax_cart', [
            'item' => $item,
            'data' => $data,
            'quantity' => $qty,
            'message' => 'Изменено количество:'
        ]);
    }

    public function actionUpdate($id, $inc, $weight = null, $options = '')
    {

        /* @var $item CartItem */
        foreach ($this->cart->getItems() as $item) {
            if ($item->getProductId() == $id && $item->weight == $weight && $item->options == $options) {
                //$quantity = $item->getQuantity() + $inc;
                $quantity = $inc;
                if ($quantity < 1) {
                    $this->cart->remove($id, $weight, $options);
                } else {
                    $this->cart->set($id, $quantity, $weight, $options);
                }
            }
        }

        $message = 'Обновлено:';

        return $this->renderAjax('ajax_cart', [
            'message' => $message
        ]);
    }

    public function actionUpdateOptions($id, $weight = null, $options = '', $newOptions = '')
    {
        if (!$product = Products::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }

        if ($options != $newOptions) {
            $editedItem = null;
            $similarItem = null;
            $cartItems = $this->cart->getItems();
            /* @var $item CartItem */
            foreach ($cartItems as $key => $item) {
                if ($item->getProductId() == $id && $item->weight == $weight && $item->options == $options) {
                    $editedItem = $item;
                }
                if ($item->getProductId() == $id && $item->weight == $weight && $item->options == $newOptions) {
                    $similarItem = $item;
                }
            }

            $similarQty = 0;
            if ($similarItem) {
                $similarQty = $similarItem->getQuantity();
                $this->cart->remove($id, $weight, $newOptions);
            }
            $this->cart->setOptions($id, $weight, $options, $newOptions);

            $editedQty = $editedItem->getQuantity();
            $qty = $editedQty + $similarQty;
            if ($editedQty != $qty) {
                $this->cart->set($id, $qty, $weight, $newOptions);
            }
        }

        $message = 'Изменены опции:';

        return $this->renderAjax('ajax_cart', [
            'data' => $product,
            'message' => $message
        ]);
    }

    public function actionRemove($id, $weight = null, $options = '')
    {
        $this->cart->remove($id, $weight, $options);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClear()
    {
        $this->cart->clear();

        return $this->redirect(['catalog/index']);
    }

    public function actionReorder($id)
    {
        if (!$oldOrder = Orders::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $message = null;
        foreach ($oldOrder->orderItems as $item) {
            if ($product = Products::findOne(['id' => $item->product_id, 'category_status' => 1, 'status' => 1])) {
                $cartItem = new CartItem($product, $item->qty_item, $item->weight, $item->options);
                $this->cart->add($cartItem);
            } else {
                $message .= $item->title . ' больше недоступен для заказа <br>';
            }
        }
        return $this->redirect(['checkout', 'message' => $message]);
    }

    public function actionCheckout($message = null)
    {
        if (!$this->cart->getItems()) {
            Yii::$app->session->setFlash('error', $message . 'Корзина пуста.');
            return $this->redirect(['catalog/index']);
        }
        if ($message) {
            Yii::$app->session->setFlash('error', $message);
        }
        $form = new OrderForm($this->cart);
        /*
        if (Yii::$app->user->isGuest) {
            $form->scenario = 'create';
        }
        */
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                if ($this->cart->getNotEnoughQtyItems()) {
                    $message = 'Недостаточное количество: <br>';
                    foreach ($this->cart->getNotEnoughQtyItems() as $notEnoughQtyItem) {
                        $message .= $notEnoughQtyItem['title'];
                        $message .= ($notEnoughQtyItem['weight']) ? ' - ' . $notEnoughQtyItem['weight'] : '';
                        $message .= ' : ' . $notEnoughQtyItem['min_qty'] . 'шт.';
                        $message .= '<br>';
                    }
                    Yii::info('Not enough items', __METHOD__ . ' orders_creating');
                    Yii::$app->session->setFlash('error', $message);
                    return $this->redirect(['cart/index']);
                }
                if (!$this->cart->isAllowedCost()) {
                    Yii::info('Not allowed cost', __METHOD__ . ' orders_creating');
                    Yii::$app->session->setFlash('error', $message . 'Сумма заказа слишком низкая.');
                    return $this->redirect(['cart/index']);
                }
                if ($order = $form->create()) {
                    Yii::info("order #{$order->id} created", __METHOD__ . ' orders_creating');
                    Yii::info($order, __METHOD__ . ' orders_creating');
                    /*
                    try {
                        $form->mail($order);
                    } catch (\RuntimeException $e) {
                        Yii::$app->errorHandler->logException($e);
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                    */

                    if ($order->pay_method == 'online') {
                        //todo вернуть
                        // $acquirer = new SberBank(); 
                        // $result = $acquirer->register($order->id);
                        //todo отсечь все возможные ложные перенаправления на завершение заказа
                        // if (!$result || isset($result['errorCode'])) {
                        //     Yii::info("order #{$order->id} online payment error. " . $result['errorCode'] . ': ' . $result['errorMessage'], __METHOD__ . ' orders_creating');
                        //     Yii::$app->session->setFlash('error', 'Произошла ошибка.<br>' .  $result['errorCode'] . ': ' . $result['errorMessage']);
                        //     return $this->redirect(['checkout']);
                        // }
                        // Yii::info("order #{$order->id} unknown payment reaction", __METHOD__ . ' orders_creating');
                        // Yii::$app->session->setFlash('error', 'Произошла ошибка при перенаправлении на страницу оплаты.');
                        // return $this->redirect(['checkout']);
                    } elseif ($order->pay_method == 'card') {

                    } elseif ($order->pay_method == 'cash') {

                    } elseif ($order->pay_method == 'cash_on_self') {

                    } elseif ($order->pay_method == 'card_on_self') {

                    } elseif ($order->pay_method == 'contract') {

                    }
                    Yii::info("order #{$order->id} success no-online checkout", __METHOD__ . ' orders_creating');
                    return $this->redirect(['cart/end-payment', 'localOrderId' => $order->id]);
                }
            } else {
                Yii::info('Validation error', __METHOD__ . ' orders_creating');
                Yii::$app->session->setFlash('error', 'Проверьте корректность заполнения формы');
            }
        }
        return $this->render('checkout', [
            'cart' => $this->cart,
            'model' => $form
        ]);
    }

    public function actionEndPayment($orderId = null, $localOrderId = null)
    {
        Yii::info("order orderId=#{$orderId} localOrderId=#{$localOrderId} end-payment page reached", __METHOD__ . ' orders_creating');
        $formXml = false;
        $setStatus = false;
        if ($localOrderId && $order = Orders::findOne($localOrderId)) {
            if ($orderId) {
                //online payment
                if (!$order->acquirer_order_id) {
                    $order->acquirer_order_id = $orderId;
                    $order->save();

                    // todo вернуть
                    // $acquirer = new SberBank();
                    // $acquirerResponse = $acquirer->orderStatus($orderId);
                    // if ($acquirerResponse['ErrorCode'] != 0) {
                    //     Yii::info("order #{$order->id} acquirer orderStatus error", __METHOD__ . ' orders_creating');
                    //     Yii::info($acquirerResponse, __METHOD__ . ' orders_creating');
                    //     return $this->redirect(['cart/checkout', 'message' => 'Оплата отклонена']);
                    // } else {
                    //     $formXml = true;
                    //     $setStatus = true;
                    // }
                    $formXml = true;
                    $setStatus = true;
                    $this->cart->clear();
                }
            } else {
                //other payments
                $formXml = true;
            }

            Yii::info("order #{$order->id} check field end_payment_reached", __METHOD__ . ' orders_creating');
            if (!$order->end_payment_reached) {
                $order->end_payment_reached = 1;
                $order->save();
                $this->cart->clear();
                Yii::info("order #{$order->id} success cart clear", __METHOD__ . ' orders_creating');
            }

            if ($formXml) {
                Yii::info("order #{$order->id} try form xml, send sms, send email", __METHOD__ . ' orders_creating');
                if (!$order->xml_formed) {
                    $order->formXml();
                    $order->xml_formed = 1;

                    if ($setStatus) {
                        //todo webhooks from acquirer
                        $order->status = 1;
                    }

                    $order->sendSMS();

                    $order->save();

                    try {
                        $order->mail();
                        $order->email_result = 'OK';
                    } catch (\RuntimeException $e) {
                        Yii::info("order #{$order->id} send email error", __METHOD__ . ' orders_creating');
                        $order->email_result = $e->getMessage();
                    }

                    $order->save();
                }
            }
        }

        return $this->render('end_payment', [
            'orderId' => $orderId,
            'localOrderId' => $localOrderId,
        ]);
    }

    public function actionFailPayment($orderId)
    {
        Yii::info("order #{$orderId} fail payment", __METHOD__ . ' orders_creating');
        Yii::$app->session->setFlash('error', 'Произошла ошибка при оплате.');
        return $this->redirect(['checkout']);
    }

    public function actionGetDeliveryTimeIntervals($date = null, $delivery_self = null, $shape_id = null, $inner_mkad = null)
    {
        return $this->renderAjax('_delivery_time_intervals', [
            'date' => $date,
            'delivery_self' => $delivery_self,
            'shape_id' => $shape_id,
            'inner_mkad' => $inner_mkad,
        ]);
    }

}