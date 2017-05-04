<?php
/**
 * Created by PhpStorm.
 * User: HaoMingyang
 * Date: 2017/4/18
 * Time: 14:22
 */
namespace Sanv\Banner\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Sanv_Banner';
        $this->_headerText = __('Banners');
        $this->_addButtonLabel = __('Create New Banner');
        parent::_construct();
    }
}
