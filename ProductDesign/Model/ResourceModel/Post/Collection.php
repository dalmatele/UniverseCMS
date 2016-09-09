<?php

namespace dalmate\ProductDesign\Model\ResourceModel\Post;

/**
 * Description of Collection
 *
 * @author duc
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dalmate\ProductDesign\Model\Post', 'dalmate\ProductDesign\Model\ResourceModel\Post');
    }
}
