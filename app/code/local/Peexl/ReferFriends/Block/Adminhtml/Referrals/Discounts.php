<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
/*
 * Discounts grid class
 */
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    
    public function __construct()
    {
        $this->_addButtonLabel = Mage::helper('peexl_referfriends')->__("Add new Discount");
        $this->_blockGroup = 'peexl_referfriends';
        $this->_controller = 'adminhtml_referrals_discounts';          
        $this->_headerText = Mage::helper('peexl_referfriends')->__("Manage Bonus Discounts");
 
        parent::__construct();
      //  $this->_removeButton('add');
    }
    protected function _prepareLayout()
   {
       $this->setChild( 'grid',
           $this->getLayout()->createBlock( $this->_blockGroup.'/' . $this->_controller . '_grid',
           $this->_controller . '.grid')->setSaveParametersInSession(true) );
       return parent::_prepareLayout();
   }
   
   
    
}