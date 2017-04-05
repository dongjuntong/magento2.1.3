<?php
namespace Emizentech\ShopByBrand\Block\Adminhtml;

class Items extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'items';
        $this->_headerText = __('Items');
        $this->_addButtonLabel = __('Add Option');
        parent::_construct();

        $this->addButton('import_brand', [
            'label'   => __('Import Brands'),
            'onclick' => "setLocation('{$this->getUrl('*/*/importbrand')}')",
            'class'   => 'add',
        ], -1);

    }
}
