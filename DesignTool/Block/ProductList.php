<?php

namespace dal\DesignTool\Block;

use dal\DesignTool\Api\Data\DesignToolInterface;

/**
 * Description of ProductList
 *
 * @author duc
 */
class ProductList extends \Magento\Framework\View\Element\Template implements 
\Magento\Framework\DataObject\IdentityInterface{
    protected $_productCollectionFactory;
    
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
     \dal\DesignTool\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
            array $data = []){
        parent::__construct($context, $data);
        $this->_productCollectionFactory = $productCollectionFactory;
    }
    
    public function getProducts(){
        if(!$this->hasData("products")){
            
                    
        }
    }
}
