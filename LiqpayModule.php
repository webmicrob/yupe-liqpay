<?php

use yupe\components\WebModule;

/**
 * Class LiqpayModule
 */
class LiqpayModule extends yupe\components\WebModule
{
    /**
     *
     */
    const VERSION = '0.1';

    /**
     * @return array
     */
    public function getDependencies()
    {
        return ['payment'];
    }

    /**
     * @return bool
     */
    public function getNavigation()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getAdminPageLink()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getIsShowInAdminMenu()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * @return array
     */
    public function getEditableParams()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return Yii::t('LiqpayModule.liqpay', 'Store');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Yii::t('LiqpayModule.liqpay', 'LiqPay');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Yii::t('LiqpayModule.liqpay', 'LiqPay payment module');
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return Yii::t('LiqpayModule.liqpay', 'webmicrob');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Yii::t('LiqpayModule.liqpay', 'http://ubeg.eu');
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return Yii::t('LiqpayModule.liqpay', 'webmicrob@gmail.com');
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa fa-credit-card';
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
    }
}
