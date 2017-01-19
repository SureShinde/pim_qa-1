<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Helper_Data extends Mage_Catalog_Helper_Data {

    const XML_NODE_PACKAGE_PRODUCT_TYPE = 'global/catalog/product/type/package';
    const XML_NODE_FRONTEND_FRONTNAME = 'frontend/routers/packagebuilder/args';

    protected $_session;

    public function getProduct() {
        return Mage::registry('product');
    }

    public function getPackageSession() {
        if (!$this->_session) {
            $this->_session = Mage::getSingleton('packagebuilder/package_session')->getPackageSession($this->getProduct());
        }
        return $this->_session;
    }
    public function resetPackageSession() {
        Mage::getSingleton('packagebuilder/package_session')->resetSession();
        $this->_session = null;
    }

    public function getPackageSessionById($packageId) {
        if (!$this->_session) {
            $this->_session = Mage::getSingleton('packagebuilder/package_session')->getPackageSessionById($packageId);
        }
        return $this->_session;
    }

    public function getPackagesSession() {
        return Mage::getSingleton('packagebuilder/package_session');
    }

    public function getComponents() {
        return $this->getPackageSession()->getComponents();
    }

    public function getActiveComponent() {
        return $this->getPackageSession()->getActiveComponent();
    }

    public function getItemOptionViewUrl($productId, $package = "", $item = "") {
        return Mage::getUrl(
                        $this->getModuleFrontname() . '/product/view', array(
                    'id' => $productId,
                    'package' => ($package) ? $package : Mage::registry('current_package_id'),
                    'item' => ($item) ? $item : Mage::registry('current_item_id')
                        )
        );
    }

    public function getPackageAddItemOptionUrl($productId) {
        return Mage::getUrl(
                        $this->getModuleFrontname() . '/package/add', array(
                    'product' => $productId,
                    'package' => Mage::registry('current_package_id'),
                    'item' => Mage::registry('current_item_id')
                        )
        );
    }

    public function getAllowedSelectionTypes() {
        $config = Mage::getConfig()->getNode(self::XML_NODE_PACKAGE_PRODUCT_TYPE);
        return array_keys($config->allowed_selection_types->asArray());
    }

    public function getModuleFrontname() {
        $config = Mage::getConfig()->getNode(self::XML_NODE_FRONTEND_FRONTNAME);
        return $config->frontName;
    }
    
    public function getOptionByProductId($itemId, $productId) {
        $options = Mage::getModel('packagebuilder/package_item_option')
                ->getCollection()
                ->addFieldToFilter('item_id', $itemId)
                ->addFieldToFilter('product_id', $productId);
        return $options->getFirstItem();
    }

}

