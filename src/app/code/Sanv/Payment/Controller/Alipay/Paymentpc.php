<?php
/**
 * Created by PhpStorm.
 * User: HaoMingyang
 * Date: 2017/3/15
 * Time: 15:24
 */

namespace Sanv\Payment\Controller\Alipay;

class Paymentpc extends \Magento\Framework\App\Action\Action
{
    protected $alipayConfig;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Sanv\Payment\Model\Payment $alipayConfig
    )
    {
        parent::__construct($context);
        $this->alipayConfig = $alipayConfig;
        $this->messageManager = $context->getMessageManager();
    }

    public function execute()
    {
        $url = $this->alipayConfig->getOrderPlaceRedirectUrl();
        return $this->_redirect($url);

    }

}