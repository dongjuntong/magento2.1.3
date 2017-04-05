<?php
namespace Sanv\Widgetdemo\Block\Widget;
class Demo extends \Magento\Framework\View\Element\Template
    implements \Magento\Widget\Block\BlockInterface
{
    protected $_href;
    protected $_src;
    protected $_order;
    protected $_size;
    protected $_background;
    protected $_title;
    protected $_shortdesc;
    protected $_showshade;
    protected $_shadecolor;
    protected $_show;
    protected $_buttonval;
    protected $_description;
    protected $_template;

    public function addData(array $arr)
    {
        // TODO: Implement addData() method.
    }

    public function setData($key, $value = null)
    {
        // TODO: Implement setData() method.
    }

    public function getHref()
    {
        if (!$this->_href) {
            $this->_href = '';
            if ($this->getData('img_link')) {
                $this->_href = $this->getData('img_link');
            }
        }
        return $this->_href;
    }
    public function getSrc() {
        if (!$this->_src) {
            $this->_src = '';
            if ($this->getData('image')) {
                $this->_src = $this->getData('image');
            }
        }
        return $this->_src;
    }
    public function getSize() {
        if (!$this->_size) {
            $this->_size = 1;
            if ($this->getData('img_size')) {
                $this->_size = $this->getData('img_size');
            }
        }
        return $this->_size;
    }
    public function getImageOrder() {
        if (!$this->_order) {
            $this->_order = 1;
            if ($this->getData('img_order')) {
                $this->_order = $this->getData('img_order');
            }
        }
        return $this->_order;
    }
    public function getBackground() {
        if (!$this->_background) {
            $this->_background = '#fff';
            if ($this->getData('color')) {
                $this->_background = $this->getData('color');
            }
        }
        return $this->_background;
    }
    public function getTitle() {
        if (!$this->_title) {
            if ($this->getData('title')) {
                $this->_title = $this->getData('title');
            }
        }
        return $this->_title;
    }
    public function getshortDesc() {
        if (!$this->_shortdesc) {
            if ($this->getData('short_description')) {
                $this->_shortdesc = $this->getData('short_description');
            }
        }
        return $this->_shortdesc;
    }
    public function getshowShade() {
        $this->_showshade = 0;
        if ($this->getData('show_shade')) {
            $this->_showshade = 1;
        }
        return $this->_showshade;
    }
    public function getshadeColor() {
        if ($this->_showshade) {
            if ($this->getData('shade_color')) {
                $this->_shadecolor = $this->getData('shade_color');
            }
        }
        return $this->_shadecolor;
    }
    public function getButtonval() {
        if (!$this->_buttonval) {
            if ($this->getData('button_val')) {
                $this->_buttonval = $this->getData('button_val');
            }
        }
        return $this->_buttonval;
    }
    public function getDesc() {
        if (!$this->_description) {
            if ($this->getData('description')) {
                $this->_description = $this->getData('description');
            }
        }
        return $this->_description;
    }
}
