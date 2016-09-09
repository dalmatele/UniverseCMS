<?php

namespace dal\DesignTool\Model\ResourceModel\Product;

/**
 * Description of Collection
 *
 * @author duc
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
    
    protected $_idFieldName = "product_id";
    
    protected function _construct() {
        $this->_init("dal\DesignTool\Model\DesignTool", "dal\DesignTool\Model\ResourceModel\Product");
    }
}
