<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace Emizentech\ShopByBrand\Block\Adminhtml\Items\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;


class Main extends Generic implements TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Emizentech\ShopByBrand\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Emizentech\ShopByBrand\Model\Status $status,
        array $data = []
    )
    {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Brand Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Brand Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_emizentech_shopbybrand_items');
        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
            $fieldset->addField('option_id', 'hidden', ['name' => 'option_id']);
        }

        $fieldset->addField(
            'admin_lable',
            'text',
            ['name' => 'admin_lable', 'label' => __('Admin Lable'), 'title' => __('Admin Lable'), 'required' => true]
        );

        $fieldset->addField(
            'front_lable',
            'text',
            ['name' => 'front_lable', 'label' => __('Default Store View'), 'title' => __('Default Store View'), 'required' => false]
        );

        $fieldset->addField(
            'src',
            'image',
            ['name' => 'src', 'label' => __('Upload Image'), 'title' => __('Upload Image'), 'required' => false, 'disable' => $isElementDisabled]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            ['name' => 'sort_order', 'label' => __('Sort Order'), 'title' => __('Sort Order'), 'required' => false]
        );
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
