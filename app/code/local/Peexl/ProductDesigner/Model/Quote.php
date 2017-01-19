<?php

class Peexl_ProductDesigner_Model_Quote extends Mage_Sales_Model_Quote {

    /**
     * Retrieve quote item by product id
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  Mage_Sales_Model_Quote_Item || false
     */
    public function getItemByProduct($product) {
        if (Mage::getSingleton('core/session')->getCurrentProductId() == $product->getId()) {
            return false;
        }
        foreach ($this->getAllItems() as $item) {
            if ($item->representProduct($product)) {
                return $item;
            }
        }
        return false;
    }

}
