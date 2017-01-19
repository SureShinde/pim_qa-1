<?php
 
class Icube_Pim_Block_Adminhtml_Vendor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'pim';
        $this->_controller = 'adminhtml_vendor';
 
        $this->_updateButton('save', 'label', Mage::helper('pim')->__('Save Vendor'));
        $this->_removeButton('delete');
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('vendor_data') && Mage::registry('vendor_data')->getId() ) {
            return Mage::helper('pim')->__("Edit '%s'", $this->htmlEscape(Mage::registry('vendor_data')->getVendorName()));
        } else {
            return Mage::helper('pim')->__('Add Vendor');
        }
    }
}