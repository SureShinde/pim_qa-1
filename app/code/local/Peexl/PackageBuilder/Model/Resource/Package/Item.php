<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Resource_Package_Item extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('packagebuilder/package_item', 'item_id');
    }

    public function saveOptions($itemId, $options) {
        $optionsTable = $this->getTable('package_item_option');
        
        $productIds = array();
        foreach($options as $option) {
          $productIds[] = trim($option['product_id']);
        }
        
        // Delete unused options
        $flatIds = implode(",", $productIds);
        $this->_getWriteAdapter()->delete($optionsTable, $this->_getWriteAdapter()->quoteInto("item_id=? AND product_id NOT IN ($flatIds)", $itemId));

        // Add options to package item
        $currentProductIds = $this->_getReadAdapter()->fetchCol("SELECT product_id FROM $optionsTable WHERE item_id = ?", $itemId);
        foreach ($options as $option) {
            $productId = trim($option['product_id']);
            $previewImage = trim($option['preview_image']);
            if (!in_array($productId, $currentProductIds)) {
                $this->_getWriteAdapter()->insert($optionsTable, array(
                    'item_id' => $itemId,
                    'product_id' => $productId,
                    'preview_image' => $previewImage
                ));
            } else {
              $this->_getWriteAdapter()->update($optionsTable, array(
                    'preview_image' => $previewImage
                ),  "item_id = $itemId AND product_id = $productId");
            }
        }
        return $this;
    }

}