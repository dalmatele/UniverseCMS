<?php

namespace dal\DesignTool\Api\Data;



/**
 * Description of PostInterface
 *
 * @author duc
 */
interface DesignToolInterface {
    
    const PRODUCT_ID = 'product_id';
    const PRODUCT_NAME = 'product_name';
    const PRODUCT_TYPE = 'product_type';
    const PRODUCT_OWNER = 'product_ower';
    const IS_ACTIVE = 'is_active';
    const PRODUCT_COST = 'product_cost';
    const CREATED_DATE = 'created_date';
    const UPDATED_DATE = 'updated_date';
    
    public function getProductId();
    public function getProductName();
    public function getProductType();
    public function getProductOwner();
    public function IsActive();
    public function getProductCost();
    public function getCreatedDate();
    public function getUpdatedDate();
    
    public function setProductId($id);
    public function setProductName($product_name);
    public function setProductType($product_type);
    public function setProductOwner($product_owner);
    public function setIsActive($is_active);
    public function setProductCost($product_cost);
    public function setCreatedDate($created_date);
    public function setUpdatedDate($updated_date);
}
