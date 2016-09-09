<?php

namespace dalmate\ProductDesign\Controller\ProductDesign;//it's the path of folder of this module, isn't it?

class Index extends \Magento\Framework\App\Action\Action{
    public function __construct(\Magento\Framework\App\Action\Context $context) {
        parent::__construct($context);
    }
    
    public function execute() {
        echo "Custom Module is ready";
        exit;
    }
}
