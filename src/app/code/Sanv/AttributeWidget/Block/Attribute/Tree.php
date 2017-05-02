<?php
namespace Sanv\AttributeWidget\Block\Attribute;
class Tree extends \Magento\Framework\View\Element\Template
    implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var array
     */
    protected $_Attribute;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $catalogCategoryFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    protected $_attributeManager;

    /**
     * @var \Emizentech\ShopByBrand\Model\ResourceModel\Items\CollectionFactory
     */
    protected $brandResourceAttributeCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $catalogCategoryFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $attributeManager,
        \Emizentech\ShopByBrand\Model\ResourceModel\Items\CollectionFactory $brandResourceAttributeCollectionFactory,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->catalogCategoryFactory = $catalogCategoryFactory;
        $this->_attributeManager = $attributeManager;
        $this->brandResourceAttributeCollectionFactory = $brandResourceAttributeCollectionFactory;
        parent::__construct($context,$data);
    }

    public function getAttribute()
    {
        if (!$this->_Attribute) {
            $this->_Attribute = array();
            if ($this->getData('attribute_options')) {
                $array=explode(',',$this->getData('attribute_options'));
                $attrname='brand';
                $attributeId=$this->_getAttributeId($attrname);

                $rootid=$this->storeManager->getStore()->getRootCategoryId();
                $cid=$this->catalogCategoryFactory->create()->load($rootid)->getChildren();
                $url=$this->catalogCategoryFactory->create()->load($cid)->getUrl();
                $collection=$this->brandResourceAttributeCollectionFactory->create()->addFieldToFilter('attribute_id', $attributeId)->addFieldToFilter('option_id',array('in'=>$array));
                foreach($collection as $item){
                    if(in_array($item->getData('option_id'),$array)){
                        $this->_Attribute[$item->getData('option_id')]['img']=str_replace('index.php/','',$this->_storeManager->getStore()->getBaseUrl()).'pub/media/'.$item->getData('src');
                        $this->_Attribute[$item->getData('option_id')]['url']=$url.'?'.$attrname.'='.$item->getData('option_id');
                        $this->_Attribute[$item->getData('option_id')]['lable']=$item->getData('front_lable');
                    }
                }
            }
        }
        return $this->_Attribute;
    }

    protected function _getAttributeId($code)
    {
        $attributeId = $this->_attributeManager->getIdByCode('catalog_product',$code);
        return $attributeId;
    }

}