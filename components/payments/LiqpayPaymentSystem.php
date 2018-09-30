<?php

/**
 * Class LiqpayPaymentSystem
 * @link https://www.liqpay.com/ru/doc/
 */

Yii::import('application.modules.liqpay.LiqpayModule');
Yii::import('application.modules.liqpay.components.LiqPay');
Yii::import('application.modules.liqpay.models.LiqpayLog');

/**
 * Class LiqpayPaymentSystem
 */
class LiqpayPaymentSystem extends PaymentSystem
{
    /**
     * @param Payment $payment
     * @param Order $order
     * @param bool|false $return
     * @return mixed|string
     */
    public function renderCheckoutForm(Payment $payment, Order $order, $return = false)
    {
        $settings = $payment->getPaymentSystemSettings();
        $liqpay = new LiqPay($settings['public_key'], $settings['private_key']);

        $description = Yii::t('LiqpayModule.liqpay', 'Payment order #{id} on "{site}" website', [
            '{id}' => $order->id,
            '{site}' => Yii::app()->getModule('yupe')->siteName
        ]);
        $description .= sprintf(' (%s, %s)', $order->name, $order->phone);

        $params = [
            'version'=>'3',
            'action'=>'pay',
            'amount'=>$order->getTotalPriceWithDelivery(),
            'currency'=>$payment->currency_id ?: 'UAH',
            'description'=> $description,
            'order_id'=>$order->id,
            'server_url'=>Yii::app()->createAbsoluteUrl('/payment/payment/process', ['id' => $payment->id]),
            'result_url'=>Yii::app()->createAbsoluteUrl('/order/order/view', ['url'=>$order->url]),
        ];

        if(1 == $settings['sandbox']) {
            $params['sandbox'] = 1;
        }

        //echo "<pre>".print_r($payment, true)."</pre>";
        //echo "<pre>".print_r($params, true)."</pre>";

        return Yii::app()->getController()->renderPartial(
            'application.modules.liqpay.views.form',
            [
                'id' => $order->id,
                'price' => Yii::app()->money->convert($order->getTotalPrice(), $payment->currency_id),
                'settings' => $payment->getPaymentSystemSettings(),
                'order' => $order,
                'params' => $params,
                'liqpay' => $liqpay,
            ],
            $return
        );
    }

    /**
     * @param Payment $payment
     * @param CHttpRequest $request
     * @return bool
     */
    public function processCheckout(Payment $payment, CHttpRequest $request)
    {
        $post_data = $request->getParam('data');
        $post_signature = $request->getParam('signature');

        // если нет данных или подписи - нам пришел неправильный запрос
        if( !$post_data || !$post_signature) {
            return FALSE;
        }

        // Получаем параметры платежной системы
        $settings = $payment->getPaymentSystemSettings();

        // Проверям правильность подписи
        if($post_signature !== base64_encode(sha1( $settings['private_key'] . $post_data . $settings['private_key'], 1 ) )) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Wrong signature in liqpay request'),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );
            Yii::log('POST-data: '.$post_data,CLogger::LEVEL_TRACE,self::LOG_CATEGORY);
            Yii::log('POST-signature: '.$post_signature,CLogger::LEVEL_TRACE,self::LOG_CATEGORY);
            return false;
        }

        // создаем модель AR
        $model = new LiqpayLog();
        $model->data = $post_data;
        $model->signature = $post_signature;
        $model->save();

        // разбираем POST[data] в массив
        if ( is_null($data = json_decode(base64_decode($post_data))) ) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Error decoding LiqPay request data'),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );
            return FALSE;
        }
/*
        foreach ($data as $k => $value) {
            if(in_array($k, $model->columns)) {
                $model->$k = $value;
            }
        }*/
        $model->attributes = (array)$data;
        $model->save();

        // Order id
        $orderId = intval($model->order_id);
        // find order in database
        $order = Order::model()->findByPk($orderId);

        // if no order found
        if (null === $order) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Order with id = {id} not found!', ['{id}' => $orderId]),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );

            return false;
        }

        // check if order is already paid
        if ($order->isPaid()) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Order with id = {id} already payed!', ['{id}' => $orderId]),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );

            return false;
        }

        // check if order total == payment total
        if ($model->amount != Yii::app()->money->convert($order->getTotalPriceWithDelivery(), $payment->currency_id)) {
            Yii::log(
                Yii::t(
                    'LiqpayModule.liqpay',
                    'Error pay order with id = {id}! Incorrect price!',
                    ['{id}' => $orderId]
                ),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );

            return false;
        }

        // check, if liqpay payment is successfull
        if( !in_array($data->status, ['success','sandbox']) ) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Order #{id}: payment not successfull! (log #{log_id})', ['{id}' => $orderId, '{log_id}' => $model->id]),
                CLogger::LEVEL_INFO,
                self::LOG_CATEGORY
            );
            return FALSE;
        }

        // try to change order status to 'paid'
        if ($order->pay($payment)) {
            Yii::log(
                Yii::t('LiqpayModule.liqpay', 'Success pay order with id = {id}!', ['{id}' => $orderId]),
                CLogger::LEVEL_INFO,
                self::LOG_CATEGORY
            );

            return true;
        } else {
            Yii::log(
                Yii::t(
                    'LiqpayModule.liqpay',
                    'Error pay order with id = {id}! Error change status!',
                    ['{id}' => $orderId]
                ),
                CLogger::LEVEL_ERROR,
                self::LOG_CATEGORY
            );

            return false;
        }
    }
}
