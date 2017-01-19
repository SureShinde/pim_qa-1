<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid_Renderer_Description extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    
    /*Renders the grid content for the description column*/
    public function render(Varien_Object $row) {
        
        $value = $row->getData($this->getColumn()->getIndex());
        $description='';
        switch ($value){
            case 'referral_registration_bonus':
                $description=Mage::helper('peexl_referfriends')->__('Bonus For Friend Registration');
                break;
            case 'referral_purchase_bonus':
                $description=Mage::helper('peexl_referfriends')->__('Bonus For Friend Purchase');
                break;
            case 'customer_purchase_with_bonus':
                $description=Mage::helper('peexl_referfriends')->__('Customer Spent Bonus');
                break;
           
        }
        
      return $description;
    }

}


