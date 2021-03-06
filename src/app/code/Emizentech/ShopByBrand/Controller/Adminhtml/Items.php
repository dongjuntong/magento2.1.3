<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml;

/**
 * Items controller
 */
abstract class Items extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $eavSetup;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $_attrOptionCollectionFactory;

    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Eav\Setup\EavSetup $eavSetup
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Eav\Setup\EavSetup $eavSetup
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->eavSetup = $eavSetup;
    }

    /**
     * Initiate action
     *
     * @return this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Emizentech_ShopByBrand::items')->_addBreadcrumb(__('Items'), __('Items'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Emizentech_ShopByBrand::items');
    }

    protected function _getAttributeId()
    {
        $attr = $this->_objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute');
        $attributeId = $attr->getIdByCode('catalog_product', 'brand');
        return $attributeId;
    }
    protected function _getOptionId()
    {
        $aid=$this->_getAttributeId();
        $collection = $this->_attrOptionCollectionFactory->create()->setAttributeFilter($aid)->load();
        $attributeOptions=$collection->toOptionArray();
        $index=count($attributeOptions)-1;
        return $attributeOptions[$index]['value'];
    }
}
