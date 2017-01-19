<?php

/**
 * Product type price model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Peexl_ProductDesigner_Model_Product_Price extends Mage_Catalog_Model_Product_Type_Price {

    /**
     * Default action to get price of product
     *
     * @return decimal
     */
    public function getPrice($product) {
        $price = 0;
        $designProducts = Mage::getSingleton('core/session')->getDesignProducts();
        if (is_array($designProducts) && count($designProducts)) {
            if (isset($designProducts[$product->getId()])) {
                $designId = $designProducts[$product->getId()];
                if($product->getDesignId()) {
                    $designId = $product->getDesignId();
                }
                $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
                $designXml = new SimpleXMLElement(rawurldecode($design->getValue()));
                $designAttributes = $designXml->attributes();
                //$productAttributes = $designXml->product[0]->attributes();
                return $designAttributes['price'];
            }
            return $product->getData('price') + $price;
        }
        
        return parent::getPrice($product);
        
    }

}
