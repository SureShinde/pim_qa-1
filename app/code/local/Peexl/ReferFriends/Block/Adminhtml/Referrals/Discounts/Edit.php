<?php
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
 
        $this->_objectId = 'id';
        $this->_blockGroup = 'peexl_referfriends';
        $this->_controller = 'adminhtml_referrals_discounts';
        $this->_mode = 'edit';
 
        $this->_addButton('save_and_continue', array(
                  'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                  'onclick' => 'saveAndContinueEdit()',
                  'class' => 'save',
        ), -100);
        $this->_updateButton('save', 'label', Mage::helper('peexl_referfriends')->__('Save Discount'));
 
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
 
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
 
    public function getHeaderText()
    {
        if (Mage::registry('px_referfriends_discount_data') && Mage::registry('px_referfriends_discount_data')->getId())
        {
            return Mage::helper('peexl_referfriends')->__('Edit Discount "%s"', $this->htmlEscape(Mage::registry('px_referfriends_discount_data')->getId()));
        } else {
            return Mage::helper('peexl_referfriends')->__('New Discount');
        }
    }
 
    protected function _prepareLayout()
    {
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'));
        }
        return parent::_prepareLayout();
    }
}