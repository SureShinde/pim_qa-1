<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */

class Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_ReferalAmountPurchases extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    
    /*Renders the grid content for the customer referral's purchase amount column*/
    public function render(Varien_Object $row) {
        
        $value = $row->getData($this->getColumn()->getIndex());
      return  Mage::helper('core')->currency(Mage::helper('peexl_referfriends')->getCustomerReferalPurchases($value,'total'),true,false);
    }

}
