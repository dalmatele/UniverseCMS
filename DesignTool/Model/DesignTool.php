<?php


namespace dal\DesignTool\Model;

use dal\DesignTool\Api\Data\DesignToolInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Description of DesignTool
 *
 * @author duc
 */
class DesignTool extends \Magento\Framework\Model\AbstractModel implements DesignToolInterface,IdentityInterface{//put your code here
    
    const CACHE_TAG = 'design_tool';
    
    protected function __construct() {
        $this->_init("dal\DesignTool\Model\ResourceModel\DesignTool");
    }
    
    /**
     * Return unique ID(s) for each object in system
     * @return type
     */
    public function getIdentities(){
        return [self::CACHE_TAG.'_'.$this->getProductId()];
    }
    
    public function getProductId() {
        return $this->getData(self::PRODUCT_ID);
    }
    
    public function getProductName(){
        return $this->getData(self::PRODUCT_NAME);
    }
    
    public function getProductType(){
        return $this->getData(self::PRODUCT_TYPE);
    }
    
    public function getProductOwner(){
        return $this->getData(self::PRODUCT_OWNER);
    }
    
    
    public function IsActive(){
        return $this->getData(self::IS_ACTIVE);
    }
    
    public function getProductCost(){
        return $this->getData(self::PRODUCT_COST);
    }
    
    public function getCreatedDate(){
        return $this->getData(self::CREATED_DATE);
    }
    
    public function getUpdatedDate(){
        return $this->getData(self::UPDATED_DATE);
    }
    
    public function setProductId($id){
        return $this->setData(self::PRODUCT_ID, $id);
    }
    
    public function setProductName($product_name){
        return $this->setData(self::PRODUCT_NAME, $product_name);
    }
    
    public function setProductType($product_type){
        return $this->setData(self::PRODUCT_TYPE, $product_type);
    }
    
    public function setProductOwner($product_owner){
        return $this->setData(self::PRODUCT_OWNER, $product_owner);
    }
    
    public function setIsActive($is_active){
        return $this->setData(self::IS_ACTIVE, $is_active);
    }
    
    public function setProductCost($product_cost){
        return $this->setData(self::PRODUCT_COST, $product_cost);
    }
    
    public function setCreatedDate($created_date){
        return $this->setData(self::CREATED_DATE, $created_date);
    }
    
    public function setUpdatedDate($updated_date){
        return $this->setData(self::UPDATED_DATE, $updated_date);
    }
}
