<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */


class Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    
    /*Renders the grid content for the customer column*/
    public function render(Varien_Object $row) {
        
        $value = $row->getData($this->getColumn()->getIndex());
       
        if($value){
            $customerData = Mage::getModel('customer/customer')->load($value);
        }
        else{
            $customerData = Mage::getModel('customer/customer')->load($row->getData('customer_id'));
        }
        
        return $customerData->getEmail();
    }

}

