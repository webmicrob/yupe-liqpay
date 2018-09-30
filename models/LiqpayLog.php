<?php

/**
 * Created by PhpStorm.
 * User: microb
 * Date: 21.09.2016
 * Time: 15:23
 */

/**
 * This is the model class for table "LiqpayLog".
 *
 * The followings are the available columns in table 'store_payment_liqpay_log':
 *
 * @property integer $id
 * @property string  $data                   Данные из $_POST[data]
 * @property string  $signature              Данные из $_POST[signature]
 * @property string  $create_time            дата и время
 * @property string  $err_code               Код ошибки.
 * @property string  $err_description        Описание ошибки.
 * @property string  $version                Версия API.
 * @property string  $status                 Статус платежа.
 * @property string  $type                   Тип платежа.
 * @property string  $err_erc                Код ошибки.
 * @property string  $redirect_to            Ссылка на которую необходимо перенаправить клиента для прохождения 3DS верификации.
 * @property string  $token                  Token платежа.
 * @property string  $card_token             Token карты оправителя
 * @property integer $payment_id             Id платежа в системе LiqPay
 * @property string  $public_key             Публичный ключ магазина
 * @property integer $acq_id                 ID еквайера
 * @property integer $order_id               Order_id платежа
 * @property string  $liqpay_order_id        Order_id платежа в системе Liqpay
 * @property string  $description            Комментарий к платежу
 * @property string  $sender_phone           Телефон оправителя
 * @property string  $sender_card_mask2      Карта отправителя
 * @property string  $sender_card_bank       Банк отправителя
 * @property string  $sender_card_country    Страна карты отправителя. Цифровой ISO 3166-1 код
 * @property string  $ip                     IP адрес отправителя
 * @property string  $info                   Дополнительная информация о платеже
 * @property string  $customer               Уникальный идентификатор пользователя на сайте мерчанта. Максимальная длина 100 символов.
 * @property float   $amount                 Сумма платежа
 * @property string  $currency               Валюта платежа [USD, EUR, RUB, UAH, BYN, KZT]
 * @property string  $sender_commission      Комиссия с отправителя в валюте платежа
 * @property string  $receiver_commission    Комиссия с получателя в валюте платежа
 * @property string  $agent_commission       Комиссия агента в валюте платежа
 * @property string  $completion_date        Дата списания средств
 */
class LiqpayLog extends yupe\models\YModel
{

    /**
     * Returns the static model of the specified AR class.
     *
     * @param  string $className
     * @return ImageToGallery the static model class
     */
    public static function model($className = __CLASS__) {

        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {

        return '{{store_payment_liqpay_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return [
            ['data, signature', 'required'],
            //['err_code, err_description, version, status, type, err_erc, redirect_to, token, card_token, payment_id, public_key, acq_id, order_id, liqpay_order_id, description, sender_phone, sender_card_mask2, sender_card_bank, sender_card_country, ip, info, customer, amount, currency, sender_commission, receiver_commission, agent_commission, completion_date', 'safe'],
            array('version, payment_id, acq_id', 'numerical', 'integerOnly' => TRUE),
            array('amount', 'numerical'),
            array('signature, err_code, err_description, type, err_erc, redirect_to, token, card_token, public_key, description, info', 'length', 'max' => 255),
            array('status, order_id, liqpay_order_id, sender_phone, sender_card_mask2, sender_card_bank, ip, completion_date', 'length', 'max' => 32),
            array('sender_card_country, currency', 'length', 'max' => 8),
            array('customer', 'length', 'max' => 128),
            array('sender_commission, receiver_commission, agent_commission', 'length', 'max' => 16),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, data, signature, create_time, err_code, err_description, version, status, type, err_erc, redirect_to, token, card_token, payment_id, public_key, acq_id, order_id, liqpay_order_id, description, sender_phone, sender_card_mask2, sender_card_bank, sender_card_country, ip, info, customer, amount, currency, sender_commission, receiver_commission, agent_commission, completion_date',
                  'safe',
                  'on' => 'search'),

        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations() {

        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {

        return [
            'id'                  => Yii::t('LiqpayModule.liqpay', 'id'),
            'data'                => Yii::t('LiqpayModule.liqpay', 'POST data'),
            'signature'           => Yii::t('LiqpayModule.liqpay', 'POST signature'),
            'create_time'         => Yii::t('LiqpayModule.liqpay', 'Created at'),
            'create_time'         => 'Create Time',
            'err_code'            => 'Код ошибки.',
            'err_description'     => 'Описание ошибки.',
            'version'             => 'Версия API. Текущее значение - 3',
            'status'              => 'Статус платежа. ',
            'type'                => 'Тип платежа.',
            'err_erc'             => 'Код ошибки.',
            'redirect_to'         => 'Ссылка на которую необходимо перенаправить клиента для прохождения 3DS верификации.',
            'token'               => 'Token платежа.',
            'card_token'          => 'Token карты оправителя',
            'payment_id'          => 'Id платежа в системе LiqPay',
            'public_key'          => 'Публичный ключ магазина',
            'acq_id'              => 'ID еквайера',
            'order_id'            => 'Order_id платежа',
            'liqpay_order_id'     => 'Order_id платежа в системе Liqpay',
            'description'         => 'Комментарий к платежу',
            'sender_phone'        => 'Телефон оправителя',
            'sender_card_mask2'   => 'Карта отправителя',
            'sender_card_bank'    => 'Банк отправителя',
            'sender_card_country' => 'Страна карты отправителя. Цифровой ISO 3166-1 код',
            'ip'                  => 'IP адрес отправителя',
            'info'                => 'Дополнительная информация о платеже',
            'customer'            => 'Уникальный идентификатор пользователя на сайте мерчанта. Максимальная длина 100 символов.',
            'amount'              => 'Сумма платежа',
            'currency'            => 'Валюта платежа',
            'sender_commission'   => 'Комиссия с отправителя в валюте платежа',
            'receiver_commission' => 'Комиссия с получателя в валюте платежа',
            'agent_commission'    => 'Комиссия агента в валюте платежа',
            'completion_date'     => 'Дата списания средств',
        ];
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('data',$this->data,true);
        $criteria->compare('signature',$this->signature,true);
        $criteria->compare('create_time',$this->create_time,true);
        $criteria->compare('err_code',$this->err_code,true);
        $criteria->compare('err_description',$this->err_description,true);
        $criteria->compare('version',$this->version);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('err_erc',$this->err_erc,true);
        $criteria->compare('redirect_to',$this->redirect_to,true);
        $criteria->compare('token',$this->token,true);
        $criteria->compare('card_token',$this->card_token,true);
        $criteria->compare('payment_id',$this->payment_id);
        $criteria->compare('public_key',$this->public_key,true);
        $criteria->compare('acq_id',$this->acq_id);
        $criteria->compare('order_id',$this->order_id,true);
        $criteria->compare('liqpay_order_id',$this->liqpay_order_id,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('sender_phone',$this->sender_phone,true);
        $criteria->compare('sender_card_mask2',$this->sender_card_mask2,true);
        $criteria->compare('sender_card_bank',$this->sender_card_bank,true);
        $criteria->compare('sender_card_country',$this->sender_card_country,true);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('info',$this->info,true);
        $criteria->compare('customer',$this->customer,true);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('currency',$this->currency,true);
        $criteria->compare('sender_commission',$this->sender_commission,true);
        $criteria->compare('receiver_commission',$this->receiver_commission,true);
        $criteria->compare('agent_commission',$this->agent_commission,true);
        $criteria->compare('completion_date',$this->completion_date,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->create_time = new CDbExpression('NOW()');
        }

        return parent::beforeSave();
    }
}