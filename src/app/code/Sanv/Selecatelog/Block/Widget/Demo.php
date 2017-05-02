<?php
namespace Sanv\Selecatelog\Block\Widget;
class Demo extends \Magento\Framework\View\Element\Template
    implements \Magento\Widget\Block\BlockInterface
{
    protected $_html;
    protected $_categoryFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory  $categoryFactory,
        array $data
    )
    {
        $this->_categoryFactory=$categoryFactory;
        parent::__construct($context, $data);
    }

    public function getParentCategory()
    {
        $pcid=explode('/',$this->getData('pcid'));
        $category=$this->_categoryFactory->create()->load($pcid[1]);
        $cate['name']=$category->getName();
        $cate['url']=$category->getUrl();
        $cate['description']=$category->getDescription();
        return $cate;
    }

    public function getChildCategories() {
        $html='<div class="wrapper">';
        $cids=explode(',',$this->getData('cids_path'));
        $categories=$this->_categoryFactory->create()->getCollection()->addFieldToFilter('entity_id',array('in'=>$cids));
        foreach ($categories as $item)
        {
            $category=$this->_categoryFactory->create()->load($item->getId());
            $html.='<a href="'.$category->getUrl().'" class="link" alt="'.$category->getDescription().'" title="'.$category->getName().'">'.$category->getName().'</a>';
        }
        $html.='</div>';
        return $html;
    }

}