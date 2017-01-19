<?php
 
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Rules_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getExampleData())
        {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        }
        elseif (Mage::registry('px_referfriends_rule_data'))
        {
            $data = Mage::registry('px_referfriends_rule_data')->getData();
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
 
        $fieldset = $form->addFieldset('peexl_referfriends_rule_form', array(
             'legend' =>Mage::helper('peexl_referfriends')->__('Rule Information')
        ));
 
        $fieldset->addField('total_from', 'text', array(
             'label'     => Mage::helper('peexl_referfriends')->__('Total Amount From'),
             'class'     => 'required-entry validate-number validate-greater-than-zero',
             'required'  => true,
             'name'      => 'total_from',
             'note'     => Mage::helper('peexl_referfriends')->__('Total customer order from.'),
        ));
 
         $fieldset->addField('total_to', 'text', array(
             'label'     => Mage::helper('peexl_referfriends')->__('Total Amount To'),
             'class'     => 'required-entry validate-number validate-greater-than-zero',
             'required'  => true,
             'name'      => 'total_to',
             'note'     => Mage::helper('peexl_referfriends')->__('Total to customer order from.'),
        ));
 
        $fieldset->addField('bonus', 'text', array(
             'label'     => Mage::helper('peexl_referfriends')->__('Bonus'),
             'class'     => 'required-entry validate-number validate-greater-than-zero',
             'required'  => true,
             'name'      => 'bonus',
        ));
 
        $form->setValues($data);
 
        return parent::_prepareForm();
    }
}