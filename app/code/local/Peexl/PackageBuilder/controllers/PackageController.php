<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_PackageController extends Mage_Core_Controller_Front_Action {

    protected function _initSimpleProduct() {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
            if ($product->getId()) {
                if ($product->getTypeId() == 'configurable') {
                    if ($super = $this->getRequest()->getParam('super_attribute')) {
                        $used = $product->getTypeInstance()->getProductByAttributes($super, $product);
                        if ($used->getId()) {
                            return $used;
                        }
                    } else {
                        throw new Exception("Please specify the product option(s)");
                    }
                } else {
                    return $product;
                }
            }
            
        }
        return false;
    }

    public function addAction() {
        $productId = (int) $this->getRequest()->getParam('product');
        $packageId = (int) $this->getRequest()->getParam('package');
        $itemId = (int) $this->getRequest()->getParam('item');

        if ($packageId && $itemId) {
            try {
                $product = $this->_initSimpleProduct();
                $session = Mage::helper('packagebuilder')->getPackageSessionById($packageId);
                if ($product && $product->getStockItem()->getQty() < 1) {
                    //	Mage::getSingleton('catalog/session')->addError("We apologize, " . $product->getName() . " is currently out of stock, please choose another product.");
                    //	$this->getResponse()->setRedirect($session->getUrl());
                    //	return;
                }
                $session->getItemById($itemId)->setProduct($product);
                $session->incrementActiveItem();

                $this->getResponse()->setRedirect($session->getUrl());
                return;
            } catch (Exception $e) {
                Mage::getSingleton('catalog/session')->addNotice($e->getMessage());
                $this->getResponse()->setRedirect(Mage::helper('packagebuilder')->getItemOptionViewUrl($productId, $packageId, $itemId));
            }
        }
    }

}
