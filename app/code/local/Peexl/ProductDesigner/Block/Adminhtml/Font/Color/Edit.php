<?php
 
class Peexl_ProductDesigner_Block_Adminhtml_Font_Color_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
 
        $this->_objectId = 'id';
        $this->_blockGroup = 'productdesigner';
        $this->_controller = 'adminhtml_font_color';
        $this->_mode = 'edit';
 
        $this->_addButton('save_and_continue', array(
                  'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                  'onclick' => 'saveAndContinueEdit()',
                  'class' => 'save',
        ), -100);
        $this->_updateButton('save', 'label', Mage::helper('productdesigner')->__('Save Font Color'));
 
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
        if (Mage::registry('font_color_data') && Mage::registry('font_color_data')->getId())
        {
            return Mage::helper('productdesigner')->__('Edit Font Color "%s"', $this->htmlEscape(Mage::registry('font_color_data')->getName()));
        } else {
            return Mage::helper('productdesigner')->__('New Font Color');
        }
    }
 
}