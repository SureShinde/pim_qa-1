<?php

class Peexl_ProductDesigner_Block_Product_List extends Mage_Catalog_Block_Product_List
{
    public function getAddToCartUrl($product, $additional = array())
    {
        if ($product->getDesignerEnabled() && !$this->getRequest()->getParam('design_id')) {
            return $this->getProductUrl($product, $additional);
        } elseif($product->getDesignerEnabled() && $this->getRequest()->getParam('design_id')) {
            return $this->helper('checkout/cart')->getAddUrl($product, $additional);
        }
        return parent::getAddToCartUrl($product, $additional);
    }
    
    public function getSubmitUrl($product, $additional = array())
    {
        if($product->getDesignerEnabled() && $this->getRequest()->getParam('design_id')) {
            return $this->getAddToCartUrl($product, $additional);;
        }
        return parent::getSubmitUrl($product, $additional);
    }
}
