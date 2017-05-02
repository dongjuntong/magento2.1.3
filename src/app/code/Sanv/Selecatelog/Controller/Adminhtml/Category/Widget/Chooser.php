<?php
namespace Sanv\Selecatelog\Controller\Adminhtml\Category\Widget;
use Magento\Backend\App\Action;
class Chooser extends \Magento\Backend\App\Action
{
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        //$selected = $this->getRequest()->getParam('selected', '');
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();
        $pagesGrid = $layout->createBlock(
            'Sanv\Selecatelog\Block\Adminhtml\Category\Widget\Chooser'
        )->setId(
            $uniqId
        )->setUseMassaction(
            true
        );
        $html = $pagesGrid->toHtml();
        //var_dump($html);exit;
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents($html);
    }
}