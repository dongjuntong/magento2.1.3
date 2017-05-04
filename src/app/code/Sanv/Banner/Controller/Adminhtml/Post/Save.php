<?php
namespace Sanv\Banner\Controller\Adminhtml\Post;

class Save extends \Sanv\Banner\Controller\Adminhtml\Post
{
    /**
     * Upload model
     *
     */
    protected $_uploadModel;

    /**
     * File model
     *
     */
    protected $_fileModel;

    /**
     * Image model
     *
     */
    protected $_imageModel;

    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * constructor
     *
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Sanv\Banner\Model\Upload $uploadModel,
        \Sanv\Banner\Model\Post\File $fileModel,
        \Sanv\Banner\Model\Post\Image $imageModel,
        \Magento\Backend\Model\Session $backendSession,
        \Sanv\Banner\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_uploadModel    = $uploadModel;
        $this->_fileModel      = $fileModel;
        $this->_imageModel     = $imageModel;
        $this->_backendSession = $backendSession;
        parent::__construct($postFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('post');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $post = $this->_initPost();
            $post->setData($data);
            $featuredImage = $this->_uploadModel->uploadFileAndGetName('featured_image', $this->_imageModel->getBaseDir(), $data);
            $post->setFeaturedImage($featuredImage);
            $productImage = $this->_uploadModel->uploadFileAndGetName('product_image', $this->_imageModel->getBaseDir(), $data);
            $post->setProductImage($productImage);
            $this->_eventManager->dispatch(
                'sanv_banner_post_prepare_save',
                [
                    'post' => $post,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $post->save();
                $this->messageManager->addSuccess(__('The Post has been saved.'));
                $this->_backendSession->setMageplazaHelloWorldPostData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'sanv_banner/*/edit',
                        [
                            'post_id' => $post->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('sanv_banner/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Post.'));
            }
            $this->_getSession()->setMageplazaHelloWorldPostData($data);
            $resultRedirect->setPath(
                'sanv_banner/*/edit',
                [
                    'post_id' => $post->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('sanv_banner/*/');
        return $resultRedirect;
    }
}
