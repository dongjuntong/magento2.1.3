<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml\Items;

use Emizentech\ShopByBrand\Model\Items;

class NewAction extends \Emizentech\ShopByBrand\Controller\Adminhtml\Items
{
	/**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $_attrOptionCollectionFactory;
    
    protected  $registry;

//     public function __construct(
//        \Magento\Backend\App\Action\Context $context,
//        \Magento\Framework\Registry $registry,
//        \Magento\Backend\Model\View\Result\ForwardFactory $ForwardFactory ,
//        \Magento\Framework\View\Result\PageFactory $PF,
//     	\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
//         array $data = []
//     ) {
//         parent::__construct($context, $data);
//         $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
//
//     }

    public function execute()
    {
         $this->_forward('edit');
    }
}
