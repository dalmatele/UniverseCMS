<?php

namespace dal\DesignTool\Model\ResourceModel;

/**
 * Description of DesignTool
 *
 * @author duc
 */
class DesignTool extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
    
    protected $_date;


    public function __construct(
            \Magento\Framework\Model\ResourceModel\Db\Context $context,
            \Magento\Framework\Stdlib\DateTime\DateTime $date,
            $resourcePrefix = null
            ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }
    
    protected function _construct(){
        $this->_init("dal_designtool_designtool", "product_id");
    }
    
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object) {
        if($object->isObjectNew() && !$object->hasCreationTime()){
            $object->setCreatedDate($this->_date->gmtDate());
        }
        $object->setUpdatedDate($this->_date->gmtDate());
        return parent::_beforeSave($object);
    }
}
