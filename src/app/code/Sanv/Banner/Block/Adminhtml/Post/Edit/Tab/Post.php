<?php
namespace Sanv\Banner\Block\Adminhtml\Post\Edit\Tab;

class Post extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Wysiwyg config
     * 
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Locale\Country
     */
    protected $_countryOptions;

    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_booleanOptions;

    /**
     * Sample Multiselect options
     *
     */
    protected $_sampleMultiselectOptions;

    /**
     * constructor
     * 
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Config\Model\Config\Source\Locale\Country $countryOptions
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Config\Model\Config\Source\Locale\Country $countryOptions,
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Sanv\Banner\Model\Post\Source\SampleMultiselect $sampleMultiselectOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_wysiwygConfig            = $wysiwygConfig;
        $this->_countryOptions           = $countryOptions;
        $this->_booleanOptions           = $booleanOptions;
        $this->_sampleMultiselectOptions = $sampleMultiselectOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $post = $this->_coreRegistry->registry('sanv_banner_post');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Banner Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addType('image', 'Sanv\Banner\Block\Adminhtml\Post\Helper\Image');
        $fieldset->addType('file', 'Sanv\Banner\Block\Adminhtml\Post\Helper\File');
        if ($post->getId()) {
            $fieldset->addField(
                'post_id',
                'hidden',
                ['name' => 'post_id']
            );
        }
        $fieldset->addField(
            'name',
            'editor',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'config'    => $this->_wysiwygConfig->getConfig()
            ]
        );
        $fieldset->addField(
            'middle_name',
            'editor',
            [
                'name'  => 'middle_name',
                'label' => __('Middle Name'),
                'title' => __('Middle Name'),
                'config'    => $this->_wysiwygConfig->getConfig()
            ]
        );
        $fieldset->addField(
            'post_content',
            'editor',
            [
                'name'  => 'post_content',
                'label' => __('Description'),
                'title' => __('Description'),
                'config'    => $this->_wysiwygConfig->getConfig()
            ]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
                'name'  => 'url_key',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
            ]
        );
        $fieldset->addField(
            'tags',
            'text',
            [
                'name'  => 'tags',
                'label' => __('Tags'),
                'title' => __('Tags'),
            ]
        );
        $fieldset->addField(
            'background',
            'text',
            [
                'name'  => 'background',
                'label' => __('Background'),
                'title' => __('Background'),
            ]
        );
        $fieldset->addField(
            'ds_background',
            'text',
            [
                'name'  => 'ds_background',
                'label' => __('Description Background'),
                'title' => __('Description Background'),
            ]
        );
        $fieldset->addField(
            'edge_color',
            'text',
            [
                'name'  => 'edge_color',
                'label' => __('Edge Color'),
                'title' => __('Edge Color'),
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name'  => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => $this->_booleanOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'featured_image',
            'file',
            [
                'name'  => 'featured_image',
                'label' => __('Background Image'),
                'title' => __('Background Image'),
            ]
        );
        $fieldset->addField(
            'product_image',
            'file',
            [
                'name'  => 'product_image',
                'label' => __('Product Image'),
                'title' => __('Product Image'),
            ]
        );

        $postData = $this->_session->getData('sanv_banner_post_data', true);
        if ($postData) {
            $post->addData($postData);
        } else {
            if (!$post->getId()) {
                $post->addData($post->getDefaultValues());
            }
        }
        $form->addValues($post->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banner');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
