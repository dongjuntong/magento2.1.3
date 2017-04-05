<?php
namespace Sanv\Payment\Model;

use Magento\Checkout\Model\DefaultConfigProvider as configProvider;

class DefaultConfigProvider extends configProvider
{
    public function getConfig()
    {
        $output=parent::getConfig();
        $output['alipaySuccessPageUrl'] = $this->getAlipaySuccessPageUrl();
        return $output;
    }

    public function getAlipaySuccessPageUrl()
    {
        //return $this->urlBuilder->getUrl('alipay/alipay/payment/');
        $isMobile=$this->isMobile();

        if($isMobile) {
            return $this->urlBuilder->getUrl('alipay/alipay/payment/');
        }else {
            return $this->urlBuilder->getUrl('alipay/alipay/paymentpc/');
        }
    }

    /*
     *Determine whether the PC or mobile terminal
     */

    public function isMobile()
    {
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        if (isset ($_SERVER['HTTP_VIA']))
        {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }

        return false;
    }

}
?>