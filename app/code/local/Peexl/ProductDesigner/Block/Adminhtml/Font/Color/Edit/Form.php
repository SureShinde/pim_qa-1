<?php
 
class Peexl_ProductDesigner_Block_Adminhtml_Font_Color_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getFontColorData())
        {
            $data = Mage::getSingleton('adminhtml/session')->getFontColorData();
            Mage::getSingleton('adminhtml/session')->getFontColorData(null);
        }
        elseif (Mage::registry('font_color_data'))
        {
            $data = Mage::registry('font_color_data')->getData();
        }
        else
        {
            $data = array();
        }
 
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);
 
        $this->setForm($form);
 
        $fieldset = $form->addFieldset('font_color_form', array(
             'legend' =>Mage::helper('productdesigner')->__('Font Color Information')
        ));
 
        $fieldset->addField('name', 'text', array(
             'label'     => Mage::helper('productdesigner')->__('Name'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'name',
             'note'      => Mage::helper('productdesigner')->__('The name of the color.'),
        ));
 
        $fieldset->addField('value', 'text', array(
             'label'     => Mage::helper('productdesigner')->__('Value'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'value',
             'note'      => Mage::helper('productdesigner')->__('Hex value'),
        ));
        
        $fieldset->addField('position', 'text', array(
             'label'     => Mage::helper('productdesigner')->__('Position'),
             'required'  => false,
             'name'      => 'position',
        ));
 
        $form->setValues($data);
 
        return parent::_prepareForm();
    }
}