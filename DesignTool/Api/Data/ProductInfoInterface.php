<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dal\DesignTool\Api\Data;

/**
 * Description of ProductInfoInterface
 *
 * @author duc
 */
interface ProductInfoInterface {
    
    const PRODUCT_INFO_ID = 'product_info_id';
    const PRODUCT_ID = "product_id";
    const PRODUCT_DESC = "product_desc";
    
    public function getProductInfoId();
    public function getProductId();
    public function getProductDesc();
    
    public function setProductInfoId($product_info_id);
    public function setProductId($product_id);
    public function setProductDesc($product_desc);
    
}
