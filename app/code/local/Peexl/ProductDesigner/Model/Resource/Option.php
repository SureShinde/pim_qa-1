<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Model_Resource_Option extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('productdesigner/option', 'option_id');
    }
    
    public function deleteValues($valuesIds, $productId) {
        $valuesTable = Mage::getSingleton('core/resource')->getTableName('productdesigner/option_value');
        if(is_array($valuesIds)) {
            $flatIds = implode(",", $valuesIds);
            $this->_getWriteAdapter()->delete($valuesTable, $this->_getWriteAdapter()->quoteInto("value_id IN ($flatIds) AND product_id = ?", $productId));       
        } else {
            $this->_getWriteAdapter()->delete($valuesTable, $this->_getWriteAdapter()->quoteInto("product_id = ?", $productId));
        }
        
    }
/*
    public function saveOptions($itemId, $productIds) {
        $optionsTable = $this->getTable('package_item_option');

        $flatIds = implode(",", $productIds);
        $this->_getWriteAdapter()->delete($optionsTable, $this->_getWriteAdapter()->quoteInto("item_id=? AND product_id NOT IN ($flatIds)", $itemId));

        $currentProductIds = $this->_getReadAdapter()->fetchCol("SELECT product_id FROM $optionsTable WHERE item_id = ?", $itemId);
        foreach ($productIds as $productId) {
            if (!in_array($productId, $currentProductIds)) {
                $this->_getWriteAdapter()->insert($optionsTable, array(
                    'item_id' => $itemId,
                    'product_id' => $productId
                ));
            }
        }
        return $this;
    }
*/
}