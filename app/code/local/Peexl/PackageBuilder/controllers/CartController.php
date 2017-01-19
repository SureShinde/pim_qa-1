<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_CartController extends Mage_Core_Controller_Front_Action {

    public function addAction() {
        if ($package = $this->getRequest()->getParam('package')) {
            try {
                $product = Mage::getModel('catalog/product')->load($package);
                $cart = Mage::getSingleton('checkout/cart');
                $uniqueKey = rand(0, 999999999);
                $items = $this->_prepareItems($package);
                $params = $this->getRequest()->getParams();
                $cart->addProduct($product, array_merge($params, array('items' => $items, 'uniqueKey' => $uniqueKey)));
                $cart->save();
                Mage::dispatchEvent("package_product_added_to_quote", array('quote' => $cart->getQuote(), 'package' => array('items' => $items, 'productId' => $product->getId()), 'uniqueKey' => $uniqueKey));
                Mage::dispatchEvent('checkout_cart_add_product_complete', array('product' => $product, 'request' => $this->getRequest()));
                Mage::getSingleton('packagebuilder/package_session')->removePackageSession($package);
                $this->getResponse()->setRedirect(Mage::getBaseUrl('web') . 'checkout/cart');
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->_getRefererUrl());
                return;
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->_getRefererUrl());
                return;
            }
        }
    }

    protected function _prepareItems($packageId) {
        $items = array();
        $sessionItems = Mage::helper('packagebuilder')->getPackageSessionById($packageId)->getItems();
        foreach ($sessionItems as $item) {
            $items[$item->getCode()] = array('product_id' => $item->getProduct()->getId());
        }
        return $items;
    }

}
