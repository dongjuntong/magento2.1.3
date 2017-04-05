<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml\Items;

class Delete extends \Emizentech\ShopByBrand\Controller\Adminhtml\Items
{

    private $setup;
    private $_snapshot;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Backup\Filesystem $snapshotObject,
        \Magento\Eav\Setup\EavSetup $eavSetup)
    {
        $this->setup = $setup;
        $this->_snapshot = $snapshotObject;
        parent::__construct($context, $coreRegistry, $resultForwardFactory, $resultPageFactory, $attrOptionCollectionFactory, $eavSetup);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try{
                $model = $this->_objectManager->create('Emizentech\ShopByBrand\Model\ResourceModel\Items\Collection');
                $option=$model->load($id)->getData();
                $optionTable = $this->setup->getTable('eav_attribute_option');
                $optionId=$option[0]['option_id'];
                $condition = ['option_id =?' => $optionId];
                $this->setup->getConnection()->delete($optionTable, $condition);

                try {
                    $model = $this->_objectManager->create('Emizentech\ShopByBrand\Model\Items');
                    $model->load($id);
                    $model->delete();

                    $this->messageManager->addSuccess(__('You deleted the item.'));
                    $this->_redirect('emizentech_shopbybrand/*/');
                    return;
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('We can\'t delete item right now. Please review the log and try again.')
                    );
                    $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                    $this->_redirect('emizentech_shopbybrand/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                    return;
                }

            }catch (Exception $e){
                $this->messageManager->addError(
                    __('Fail to delete the option in attribute. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('emizentech_shopbybrand/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('emizentech_shopbybrand/*/');
    }
}
