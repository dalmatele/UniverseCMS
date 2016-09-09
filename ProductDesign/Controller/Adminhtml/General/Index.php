<?php

namespace dalmate\ProductDesign\Controller\Adminhtml\General;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Description of Index
 *
 * @author duc
 */
class Index extends \Magento\Backend\App\Action{
    
    protected $resultPageFactory;
    
    public function __construct(Context $context, PageFactory $resultPageFactory) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("Dalmate_ProductDesign::general_setting");
        $resultPage->addBreadcrumb(__("Product Design"), __("Product Design"));
        $resultPage->addBreadcrumb(__('Manage Design'), __('Manage Design'));
        $resultPage->getConfig()->getTitle()->prepend(__("Product Design"));
        return $resultPage;
    }
    
    protected function _isAllowed(){
        return $this->_authorization->isAllowed("Dalmate_ProductDesign::general_setting");
    }
}
