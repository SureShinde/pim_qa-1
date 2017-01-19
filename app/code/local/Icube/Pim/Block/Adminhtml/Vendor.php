<?php
 
class Icube_Pim_Block_Adminhtml_Vendor extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'pim';
        $this->_controller = 'adminhtml_vendor';
        $this->_headerText = Mage::helper('pim')->__('Manage Vendor');
 
        parent::__construct();
    }
}