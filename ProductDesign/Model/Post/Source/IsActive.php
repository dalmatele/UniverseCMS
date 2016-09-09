<?php

namespace dalmate\ProductDesign\Model\Post\Source;

/**
 * Description of IsActive
 *
 * @author duc
 */
class IsActive implements \Magento\Framework\Data\OptionSourceInterface{
    
    protected $post;
    
    public function __construct(\dalmate\ProductDesign\Model\Post $post) {
        $this->post = $post;
    }
    
    public function toOptionArray() {
        $options[] = ["label" => "", "value" => ""];
        $availableOptions = $this->post->getAvailableStatuses();
        foreach ($availableOptions as $key => $value){
            $options[] = [
                "label" => $value,
                "value" => $key
            ];
        }
        return $options;
    }
}
