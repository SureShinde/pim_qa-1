<?php
/**
 * Catalog product model
 *
 * @method Mage_Catalog_Model_Resource_Product getResource()
 * @method Mage_Catalog_Model_Resource_Product _getResource()
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Peexl_ProductDesigner_Model_Product extends Mage_Catalog_Model_Product
{
    /**
     * Get all options of product
     *
     * @return array
     */
    public function getOptions()
    { 
        if(Mage::getModel('catalog/product')->load($this->getId())->getDesignerEnabled() && !Mage::app()->getStore()->isAdmin()) {
            return array();
        }
        return $this->_options;
    }
    
    /**
     * Load product options if they exists
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        
        if($this->getDesignerEnabled() && !Mage::app()->getStore()->isAdmin()) {
            $this->setHasOptions(false);
        }
        
        /**
         * Load product options
         */
        if ($this->getHasOptions()) {
            foreach ($this->getProductOptionsCollection() as $option) {
                $option->setProduct($this);
                $this->addOption($option);
            }
        }
        return $this;
    }
}
