<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sanv\Payment\Model;

require_once ('app/code/Sanv/Payment/alipaypc/lib/alipay_core.function.php');
require_once ('app/code/Sanv/Payment/alipaypc/lib/alipay_md5.function.php');

use Magento\Sales\Model\Order;

/**
 * Pay In Store payment method model
 */
class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'alipay';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    protected $alipay_config_sign_type;
    protected $alipay_config_key;
    public $params=array();
    protected $_isInitializeNeeded      = true;
    protected $_canUseInternal          = false;
    protected $_canUseForMultishipping  = false;
    public $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    public $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    /**
     * Return Order place redirect url
     *
     * @return string
     */

    public function sendAlipayment($order=null){
        return $this->getOrderData($order);
    }

    public function getOrderPlaceRedirectUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $data=$this->getOrderData()->params;
        if(!empty($data)) {
            $gateWay = $data['request_url'];
            unset($data['request_url']);
            $string = $this->buildRequestParaToString($data);
            return $gateWay . $string;
        }else{
            return $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getUrl('sales/order/history');
        }
    }
    /**
     * Return new Order information
     */
    public function getAlipayConfig(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $config=$objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getConfig('payment/alipay');

        if(empty($config['partent_id']) || empty($config['md5_key'])){
            return false;
        }
        //if(empty($config['direct_pay_gateway_url'])){
        $config['gateway_url']=$this->alipay_gateway_new;
        //}
        return $config;
    }

    public function getOrderData($order=null){

        if(empty($order)){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            /** @var \Magento\Sales\Model\Order $order */
            $order = $objectManager->get('Magento\Sales\Model\Order');
            // get order last id
            $lastId = $objectManager->get('Magento\Checkout\Model\Type\Onepage')->getCheckout()->getLastOrderId();
            if(empty($lastId)){
                return false;
            }
            $order = $order->loadByIncrementId((int)$lastId);

            //$order->setStatus('pending_payment');

            //get store name
            $store = $objectManager->get( 'Magento\Store\Model\StoreManagerInterface' )->getStore();
            $storeName = $store->getName();
        }

        $this->params=array();
        //config infotmation
        $this->params['service']="create_direct_pay_by_user";

        //get config from backend
        $this->params['partner']=$this->getConfigData('partent_id');

        $this->params['seller_id']=$this->getConfigData('partent_id');


        $this->alipay_config_sign_type=$this->params['sign_type']=strtoupper('MD5');

        $this->params['transport']= 'http';
        $this->params['payment_type']='1';
        $this->alipay_config_key=$this->params['key']=$this->getConfigData('md5_key');

        $this->params['anti_phishing_key']='';
        $this->params['exter_invoke_ip']='';

        //time limit
        $this->params['it_b_pay']="24h";
        $this->params['qr_pay_mode']='2';

        //notify_url and return url
        $this->params["return_url"]='alipay/alipay/verify';

        //order increment id
        $this->params['out_trade_no']= $order->getIncrementId();

        //get order total price
        $this->params['total_fee']=str_replace(',','',number_format($order->getGrandTotal(),2));

        //order name
        $this->params['subject'] = $storeName.':'.$order->getIncrementId();

        //@TODO
        //product detail
        $this->params['request_url']=$this->getConfigData('direct_pay_gateway_url');

        $this->params['_input_charset']=trim(strtolower(strtolower('UTF-8')));

        return $this;
    }

    protected function buildRequestPara($para_temp) {

        $para_filter = paraFilter($para_temp);

        $para_sort = argSort($para_filter);

        $mysign = $this->buildRequestMysign($para_sort);

        $para_sort['sign'] = $mysign;

        return $para_sort;
    }

    public function buildRequestParaToString($para_temp) {

        $para = $this->buildRequestPara($para_temp);

        $request_data = createLinkstringUrlencode($para);

        return $request_data;
    }
    protected  function buildRequestMysign($para_sort) {

        $prestr = createLinkstring($para_sort);

        $mysign = "";
        switch (strtoupper(trim($this->alipay_config_sign_type))) {
            case "MD5" :
                $mysign = md5Sign($prestr, $this->alipay_config_key);
                break;
            default :
                $mysign = "";
        }

        return $mysign;
    }

    public function verifyNotify(){
        if(empty($_POST)) {
            return false;
        }
        else {

            $isSign = $this->getSignVeryfy($_POST, $_POST["sign"]);

            $responseTxt = 'false';
            if (! empty($_POST["notify_id"])) {$responseTxt = $this->getResponse($_POST["notify_id"]);}

            if (preg_match("/true$/i",$responseTxt) && $isSign) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function verifyReturn($params){

        $isSign = $this->getSignVeryfy($params, $params["sign"]);
        $responseTxt = 'false';
        if (!empty($params["notify_id"])) {$responseTxt = $this->getResponse($params["notify_id"]);}

        if ($responseTxt && $isSign) {
            return true;
        } else {
            return false;
        }

    }

    protected function getSignVeryfy($para_temp, $sign) {

        $para_filter = paraFilter($para_temp);

        $para_sort = argSort($para_filter);

        $prestr = createLinkstring($para_sort);
        $isSgin = false;
        $this->alipay_config_sign_type='MD5';
        $this->alipay_config_key='6m9izl6bqkfr6hns5ar9c612tpzpdmyr';

        switch (strtoupper(trim($this->alipay_config_sign_type))) {
            case 'MD5' :
                $isSgin = md5Verify($prestr, $sign, $this->alipay_config_key);
                break;
            default :
                $isSgin = false;
        }
        return $isSgin;
    }

    protected function getResponse($notify_id) {

        $config=$this->getAlipayConfig();

        $transport = strtolower(trim($config['transport']));
        $partner = trim($config['partner']);

        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        }
        else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url."partner=".$partner."&notify_id=".$notify_id;

        $responseTxt = getHttpResponseGET($veryfy_url, $this->$config['cacert']);

        return $responseTxt;
    }
}
