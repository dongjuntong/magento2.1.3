<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sanv\Payment\Model;
@require_once ('app/code/Sanv/Payment/alipaypc/lib/alipay_core.function.php');
@require_once ('app/code/Sanv/Payment/alipaypc/lib/alipay_md5.function.php');
/**
 * Pay In Store payment method model
 */
class Alipay extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    const CODE = 'alipay';
    protected $_code=self::CODE;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isInitializeNeeded      = true;
    protected $_isOffline = true;

    public function getAppId() {

        return trim($this->getConfigData('app_id'));

    }

    public function getPublicKey() {

        return trim($this->getConfigData('public_key'));

    }

    public function getPrivateKey() {

        return trim($this->getConfigData('private_key'));

    }

    public function getGatewayUrl() {

        return trim($this->getConfigData('gateway_url'));

    }

    public function getNewOrderStatus() {

        return trim($this->getConfigData('order_status'));

    }

}
