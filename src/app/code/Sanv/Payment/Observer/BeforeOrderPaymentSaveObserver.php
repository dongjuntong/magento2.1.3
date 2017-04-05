<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * OfflinePayments Observer
 */
namespace Sanv\Payment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Sanv\Payment\Model\Alipay;

class BeforeOrderPaymentSaveObserver implements ObserverInterface
{
    /**
     * Sets current instructions for bank transfer account
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $observer->getEvent()->getPayment();
        $payment->getMethodInstance()->setIsInitializeNeeded(false);

        $payment->setAdditionalInformation(
            'payable_to',
            $payment->getMethodInstance()->getPayableTo()
        );
        $payment->setAdditionalInformation(
            'mailing_address',
            $payment->getMethodInstance()->getMailingAddress()
        );
    }
}
