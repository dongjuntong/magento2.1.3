<?php
namespace Sanv\Widgetdemo\Model;
class Widget extends \Magento\Widget\Model\Widget
{
    public function getWidgetDeclaration($type, $params = array(), $asIs = true)
    {
        if(key_exists("image", $params)) {
            $om = \Magento\Framework\App\ObjectManager::getInstance();
            $url = $params["image"];
            $storeManager = $om->get('\Magento\Store\Model\StoreManagerInterface');
            $baseUrl=$storeManager->getStore()->getBaseUrl();
            if(strpos($url,'/directive/___directive/') !== false) {

                $parts   = explode('/', $url);
                $key     = array_search("___directive", $parts);
                if($key !== false) {
                    $url    = $parts[$key+1];
                    $url    = base64_decode(strtr($url, '-_,', '+/='));
                    $parts  = explode('"', $url);
                    $key    = array_search("{{media url=", $parts);
                    $url    = $baseUrl.'pub/media/'.$parts[$key+1];

                    $params["image"] = $url;
                }
            }
        }
        return parent::getWidgetDeclaration($type, $params, $asIs);
    }
}