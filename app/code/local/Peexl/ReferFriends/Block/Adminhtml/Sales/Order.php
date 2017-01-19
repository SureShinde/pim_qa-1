<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Block_Adminhtml_Sales_Order extends Mage_Sales_Block_Order_Totals {
 
    protected function _initTotals() {
        parent::_initTotals();
        
        $amt = $this->getSource()->getFeeAmount();
        $baseAmt = $this->getSource()->getBaseFeeAmount();                
        if ($amt != 0) {
            $this->addTotal(new Varien_Object(array(
                        'code' => 'referal_discount',
                        'value' => $amt,
                        'base_value' => $baseAmt,
                        'label' => 'Referral Discount',
                    )), 'referal_discount');
        }
        return $this;
    }
 
}