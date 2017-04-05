<?php
namespace Sanv\Payment\Controller\Alipay;

class Payment extends \Magento\Framework\App\Action\Action
{
    protected $storeConfigFactory;
    protected $_orderFactory;
    protected $_paymentConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Sanv\Payment\Model\Alipay $paymentConfig,
        \Magento\Store\Model\Data\StoreConfigFactory $storeConfigFactory
    )
    {
        parent::__construct($context);
        $this->_orderFactory = $orderFactory;
        $this->_paymentConfig = $paymentConfig;
        $this->storeConfigFactory = $storeConfigFactory;
    }

    public function execute()
    {
        require_once dirname(dirname(dirname ( __FILE__ ))).DIRECTORY_SEPARATOR.'Alipay\wappay\service\AlipayTradeService.php';
        require_once dirname(dirname(dirname ( __FILE__ ))).DIRECTORY_SEPARATOR.'Alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder.php';
        require dirname(dirname(dirname ( __FILE__ ))).DIRECTORY_SEPARATOR.'Alipay\config.php';

        $lastId = $this->_objectManager->get('Magento\Checkout\Model\Type\Onepage')->getCheckout()->getLastOrderId();
        $store = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface' )->getStore();
        $order = $this->_orderFactory->create();
        $order->load($lastId);


        $out_trade_no=$order->getRealOrderId();

        $subject = $store->getName().':'.$out_trade_no;

        $total_amount = str_replace(',','',number_format($order->getGrandTotal(),2));

        $body = '';

        $timeout_express="1m";

        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $config['app_id']=$this->_paymentConfig->getAppId();
        $config['alipay_public_key']=$this->_paymentConfig->getPublicKey();
        $config['merchant_private_key']=$this->_paymentConfig->getPrivateKey();

        $config['gatewayUrl']=$this->_paymentConfig->getGatewayUrl();

        $config['return_url']='alipay/alipay/verify.php';
        $config['notify_url']='alipay/alipay/verify.php';

        $payResponse = new \AlipayTradeService($config);
        $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        return;
    }
}