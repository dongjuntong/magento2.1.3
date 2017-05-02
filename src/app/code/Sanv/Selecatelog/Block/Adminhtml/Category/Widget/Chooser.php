<?php
namespace Sanv\Selecatelog\Block\Adminhtml\Category\Widget;
class Chooser extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var array
     */
    protected $_selectedCategories = [];

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $_resourceCategory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category $resourceCategory,
        array $data = []
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_resourceCategory = $resourceCategory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    protected function _prepareLayout()
    {
        $this->setChild('finish_button',
            $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
                ->setData(array(
                    'label'     => __('Finish'),
                    'onclick'   =>$this->toFinsh(),
                    'class'   => 'task'
                ))
        );
        return parent::_prepareLayout();
    }

    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $uniqId = $this->mathRandom->getUniqueHash($element->getId());
        $sourceUrl = $this->getUrl('selecatelog/category_widget/chooser', ['uniq_id' => $uniqId]);

        $chooser = $this->getLayout()->createBlock(
            'Magento\Widget\Block\Adminhtml\Widget\Chooser'
        )->setElement(
            $element
        )->setConfig(
            $this->getConfig()
        )->setFieldsetId(
            $this->getFieldsetId()
        )->setSourceUrl(
            $sourceUrl
        )->setUniqId(
            $uniqId
        );

        if ($element->getValue()) {
            $page = $this->_pageFactory->create()->load((int)$element->getValue());
            if ($page->getId()) {
                $chooser->setLabel($this->escapeHtml($page->getTitle()));
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var elementLable='.$chooserJsObject.'.getElementLabel().innerHTML;
                var pageTitle = elementLable+trElement.down("td").next().innerHTML;
                if(pageTitle.replace(/^\s+|\s+$/g,"") == "Not Selected") {pageTitle=""}
                var elementValue='.$chooserJsObject.'.getElement().value;
                var pageId = elementValue+trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                ' .
            $chooserJsObject .
            '.setElementValue(pageId); 
                ' .
            $chooserJsObject .
            '.setElementLabel(pageTitle);
                ' .
            $chooserJsObject .
            '
            }
        ';
        return $js;
    }

    protected function _prepareCollection()
    {
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect('*')->addIsActiveFilter();
        $cid=2;
        $category=$this->_categoryFactory->create()->load($cid);
        $cis=$this->_resourceCategory->getAllChildren($category);
        array_shift($cis);
        $collection->addAttributeToFilter('entity_id',array('in',$cis));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            [
                'header' => __('ID'),
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'chooser_title',
            [
                'header' => __('Title'),
                'index' => 'name',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title'
            ]
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('selecatelog/category_widget/chooser', ['_current' => true]);
    }

    public function _getSelectedCategories()
    {

    }
    public function toFinsh() {

    }

}