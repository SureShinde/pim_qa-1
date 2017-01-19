<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_ProductController extends Mage_Core_Controller_Front_Action {

    protected function _initProduct() {
        Mage::dispatchEvent('catalog_controller_product_init_before', array('controller_action' => $this));
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId = (int) $this->getRequest()->getParam('id');

        if (!$productId) {
            return false;
        }

        $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

        if (!Mage::helper('catalog/product')->canShow($product)) {
            return false;
        }
        if (!in_array(Mage::app()->getStore()->getWebsiteId(), $product->getWebsiteIds())) {
            return false;
        }

        $category = null;
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $product->setCategory($category);
            Mage::register('current_category', $category);
        } elseif ($categoryId = Mage::getSingleton('catalog/session')->getLastVisitedCategoryId()) {
            if ($product->canBeShowInCategory($categoryId)) {
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $product->setCategory($category);
                Mage::register('current_category', $category);
            }
        }


        Mage::register('current_product', $product);
        Mage::register('product', $product);

        try {
            Mage::dispatchEvent('catalog_controller_product_init', array('product' => $product));
            Mage::dispatchEvent('catalog_controller_product_init_after', array('product' => $product, 'controller_action' => $this));
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $product;
    }

    protected function _initProductLayout($product) {
        $update = $this->getLayout()->getUpdate();
        $update->addHandle('default');
        $this->addActionLayoutHandles();

        $update->addHandle('PRODUCT_TYPE_' . $product->getTypeId());
        $update->addHandle('PRODUCT_' . $product->getId());

        if ($product->getPageLayout()) {
            $this->getLayout()->helper('page/layout')
                    ->applyHandle($product->getPageLayout());
        }

        $this->loadLayoutUpdates();


        $update->addUpdate($product->getCustomLayoutUpdate());

        $this->generateLayoutXml()->generateLayoutBlocks();

        if ($product->getPageLayout()) {
            $this->getLayout()->helper('page/layout')
                    ->applyTemplate($product->getPageLayout());
        }

        $currentCategory = Mage::registry('current_category');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('product-' . $product->getUrlKey());
            if ($currentCategory instanceof Mage_Catalog_Model_Category) {
                $root->addBodyClass('categorypath-' . $currentCategory->getUrlPath())
                        ->addBodyClass('category-' . $currentCategory->getUrlKey());
            }
        }
        return $this;
    }

    public function viewAction() {
        if ($product = $this->_initProduct()) {
            Mage::dispatchEvent('catalog_controller_product_view', array('product' => $product));

            if ($this->getRequest()->getParam('options')) {
                $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
                Mage::getSingleton('catalog/session')->addNotice($notice);
            }

            Mage::register('current_package_id', $this->getRequest()->getParam('package'));
            Mage::register('current_item_id', $this->getRequest()->getParam('item'));

            Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());
            Mage::getModel('catalog/design')->applyDesign($product, Mage_Catalog_Model_Design::APPLY_FOR_PRODUCT);

            $this->_initProductLayout($product);
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('tag/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();
        } else {
            if (isset($_GET['store']) && !$this->getResponse()->isRedirect()) {
                $this->_redirect('');
            } elseif (!$this->getResponse()->isRedirect()) {
                $this->_forward('noRoute');
            }
        }
    }

}