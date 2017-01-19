<?php
/**
 * @category   Peexl
 * @package    Peexl_PackageBuilder
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Observer
{
    /**
     * Setting attribute tab block for package
     *
     * @param Varien_Object $observer
     * @return Mage_Bundle_Model_Observer
     */
    public function setAttributeTabBlock($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() == 'package') {
            Mage::helper('adminhtml/catalog')
                ->setAttributeTabBlock('packagebuilder/adminhtml_product_edit_tab_attributes');
        }
        return $this;
    }
    
    public function productView($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() == 'package') {
            // reset if called
            if (Mage::app()->getRequest()->getParam('reset')) {
                Mage::helper('packagebuilder')->resetPackageSession();
            }
            
            if ($itemId = Mage::app()->getRequest()->getParam('item')) {
                Mage::helper('packagebuilder')->getPackageSession()->setActiveItem($itemId);
            }
        }
        return $this;
    }
}
