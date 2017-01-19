<?php
/**
 * @category   Peexl
 * @package    Peexl_PackageBuilder
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */

/**
 * Package product attributes tab
 */
class Peexl_PackageBuilder_Block_Adminhtml_Product_Edit_Tab_Attributes
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    /**
     * Prepare attributes form of package product
     *
     * @return void
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        
        $price = $this->getForm()->getElement('price');
        if ($price) {
            $price->setRenderer(
                $this->getLayout()->createBlock('packagebuilder/adminhtml_product_edit_tab_attributes_extend',
                    'adminhtml.catalog.product.package.edit.tab.attributes.price')->setDisableChild(true)
            );
        }
    }

    /**
     * Get current product from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        if (!$this->getData('product')){
            $this->setData('product', Mage::registry('product'));
        }
        return $this->getData('product');
    }
}
