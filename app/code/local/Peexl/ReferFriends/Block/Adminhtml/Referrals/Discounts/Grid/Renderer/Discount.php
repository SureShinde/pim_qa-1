<?php

class Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts_Grid_Renderer_Discount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        
        $value = $row->getData($this->getColumn()->getIndex());
                
        return $value." ".($row->getData('discount_type')==0?Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol():'%');
    }

}
