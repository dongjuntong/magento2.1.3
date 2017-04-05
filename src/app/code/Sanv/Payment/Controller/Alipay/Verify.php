<?php
namespace Sanv\Payment\Controller\Alipay;

class Verify extends \Magento\Framework\App\Action\Action
{

    protected $_paymentConfig;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Sanv\Payment\Model\Alipay $paymentConfig
    )
    {
        parent::__construct($context);
        $this->_paymentConfig = $paymentConfig;
    }

    public function execute()
    {
        require dirname(dirname(dirname ( __FILE__ ))).DIRECTORY_SEPARATOR.'Alipay\config.php';
        require_once dirname(dirname(dirname ( __FILE__ ))).DIRECTORY_SEPARATOR.'Alipay\wappay\service\AlipayTradeService.php';

        $config['app_id']=$this->_paymentConfig->getAppId();
        $config['alipay_public_key']=$this->_paymentConfig->getPublicKey();
        $config['merchant_private_key']=$this->_paymentConfig->getPrivateKey();

        $arr=$this->getRequest()->getParams();
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        if($result) {

            $out_trade_no = $arr['out_trade_no'];

            $trade_no = $arr['trade_no'];

            $trade_status = $arr['trade_status'];


            if($arr['trade_status'] == 'TRADE_FINISHED' || $arr['trade_status'] == 'TRADE_SUCCESS') {
                
            }

            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('checkout/onepage/success');
            return $resultRedirect;

        }else {
            $this->messageManager->addError(
                __('There was an error occurred during paying process.')
            );
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('checkout/cart');
            return $resultRedirect;
        }
    }
}