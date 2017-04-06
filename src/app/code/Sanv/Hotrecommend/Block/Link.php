<?php
namespace Sanv\Hotrecommend\Block;
class Link extends \Magento\Framework\View\Element\Template
    implements \Magento\Widget\Block\BlockInterface
{
    protected $_idpath;

    protected $_anchortext;

    public function getidpath() {
        if (!$this->_idpath) {
            $this->_idpath = '';
            if ($this->getData('id_path')) {
                $this->_idpath = $this->getData('id_path');
            }
        }
        return $this->_idpath;
    }

    public function getanchortext()
    {
        if (!$this->_anchortext) {
            $this->_anchortext = '';
            if ($this->getData('anchor_text')) {
                $this->_anchortext = $this->getData('anchor_text');
            }
        }
        return $this->_anchortext;
    }

    public function productid(){
        $idpath = $this->getidpath();
        $productid = explode('/',$idpath);
        return $productid;
    }
}