<?php

/**
 * @category   Peexl
 * @package    Peexl_PackageBilder
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Layer extends Mage_Catalog_Model_Layer {

    /**
     * Retrieve product collection
     */
    public function getProductCollection() {
        if(!Mage::registry('product') || !Mage::registry('product')->getTypeId() == 'package') {
            return parent::getProductCollection();
        }
        $packageSession = Mage::helper('packagebuilder')->getPackageSession();
        if($packageSession->getIsComplete() || !$packageSession->getItems()) {
            return Mage::getModel('catalog/product')->getCollection()
                    ->addFieldToFilter('entity_id', array('in' => '-1'));
        }
        $id = Mage::registry('product')->getId() . '_' . $packageSession->getActiveItem()->getId();
        $productIds = $packageSession->getActiveItem()->getInstance()->getProductIds(true);
        if (isset($this->_productCollections[$id])) {
            $collection = $this->_productCollections[$id];
        } else {
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                    ->addFieldToFilter('entity_id', array('in' => $productIds));
            $this->prepareProductCollection($collection);
            $this->_productCollections[$id] = $collection;
        }

        return $collection;
    }

}
