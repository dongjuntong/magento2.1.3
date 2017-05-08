<?php
namespace Sanv\Theme\Block\Html;
/**
 * Html page top menu block
 */
class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {

        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->_menu, 'block' => $this]
        );

        $this->_menu->setOutermostClass($outermostClass);
        $this->_menu->setChildrenWrapClass($childrenWrapClass);

        $html = $this->_getNewHtml($this->_menu, $childrenWrapClass);

        $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->_menu, 'transportObject' => $transportObject]
        );
        $html = $transportObject->getHtml();
        return $html;
    }

    /**
     * Add a new function to getHtml.
     *
     * @return string
     */

    protected function _getNewHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass
    ) {
        $html='';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        foreach ($children as $child) {
            $child->setLevel($childLevel);

            $_hasChildren = ($child->hasChildren()) ? 'has-children' : '';

            if($childLevel == 0) {
                $html .= '<nav class="nav_allClassify hidden-md-down">';
                $html .= '<a href="' . $child->getUrl() . '" class="nav_allClassify_link" title="' . $this->escapeHtml(__($child->getName())) . '" alt="' . $this->escapeHtml(__($child->getName())) . '">' . $this->escapeHtml(__($child->getName())) . '</a>';
                $html .= '</nav>';
            }elseif($childLevel== 1){
                $html .= '<nav class="menu_links">';
                $html .= '<a href="' . $child->getUrl() . '" data-href="#'.$child->getId() . '" class="menu_link"  title="' . $this->escapeHtml(__($child->getName())) . '" alt="' . $this->escapeHtml(($child->getName())) . '">' . $this->escapeHtml(($child->getName())) . '<span class="fa fa-angle-right"></span></a>';
            }elseif($childLevel== 2){
                $html .= '<nav class="menu_content_sec">';
                $html .= '<a href="' . $child->getUrl() . '" class="menu_content_sec_link" title="' . $this->escapeHtml(__($child->getName())) . '" alt="' . $this->escapeHtml(__($child->getName())) . '">'. $this->escapeHtml(__($child->getName())) .'<span class="fa fa-angle-right"></span></a>';
            }else{
                $html .= '<a href="' . $child->getUrl() . '" class="menu_content_third_link" title="' . $this->escapeHtml(__($child->getName())) . '" alt="' . $this->escapeHtml(__($child->getName())) . '">' . $this->escapeHtml(__($child->getName())) . '</a>';
            }

            if(!empty($_hasChildren)){
                if($childLevel== 0){
                    if ($this->_request->getRouteName() == 'cms' || $this->_request->getActionName() == 'index') {
                        $html .= '<nav class="menu hidden-md-down menu--desktop">';
                    }else {
                        $html .= '<nav class="menu hidden-md-down menu--desktop hidden">';
                    }
                }elseif($childLevel == 1){
                    $html .= '<nav class="menu_content_scroll" id="'.$child->getId() . '">';
                    $html .= '<div class="wrapper">';
                }elseif($childLevel== 2){
                    $html .= '<nav class="menu_content_sec_content">';
                }else{
                    $html .= '';
                }

                $html.=$this->_getNewHtml($child,$childrenWrapClass);

                if($childLevel == 0 || $childLevel== 2){
                    $html .= '</nav>';
                }elseif($childLevel== 1){
                    $html .= '</div>';
                    $html .='<nav class="menu_content_brand">';
                    $html .='<a href="#" class="menu_content_brand_link" alt="" title=""><img src="" title="" alt=""/></a>';
                    $html .='</nav>';
                    $html .= '</nav>';
                }else {
                    $html .= '';
                }
            }
            if($childLevel==1 || $childLevel==2) {
                $html .= '</nav>';
            }

        }
        return $html;
    }
}
