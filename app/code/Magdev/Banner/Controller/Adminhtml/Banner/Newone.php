<?php
namespace Magdev\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Newone extends Action
{
    const ADMIN_RESOURCE = 'Magdev_Banner::banner';

    protected $resultPageFactory;

    public function __construct(Action\Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magdev_Banner::banner');
        $resultPage->getConfig()->getTitle()->prepend(__('Banners'));
        return $resultPage;
    }
    
    public function getAdminuserame()
    {
        $resultPage = "Test Admin";
        return $resultPage;
    }
}
