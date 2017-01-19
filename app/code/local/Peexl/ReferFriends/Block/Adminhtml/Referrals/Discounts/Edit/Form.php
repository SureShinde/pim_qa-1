<?php

class Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        if (Mage::getSingleton('adminhtml/session')->getExampleData()) {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        } elseif (Mage::registry('px_referfriends_discount_data')) {
            $data = Mage::registry('px_referfriends_discount_data')->getData();
        } else {
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

        $fieldset = $form->addFieldset('peexl_referfriends_discount_form', array(
            'legend' => Mage::helper('peexl_referfriends')->__('Discount Information')
        ));

        $fieldset->addField('total_from', 'text', array(
            'label' => Mage::helper('peexl_referfriends')->__('Total Bonus From'),
            'class' => 'required-entry validate-number validate-greater-than-zero',
            'required' => true,
            'name' => 'total_from',
            'note' => Mage::helper('peexl_referfriends')->__('Total customer bonuses from.'),
        ));

        $fieldset->addField('total_to', 'text', array(
            'label' => Mage::helper('peexl_referfriends')->__('Total Bonuses To'),
            'class' => 'required-entry validate-number validate-greater-than-zero',
            'required' => true,
            'name' => 'total_to',
            'note' => Mage::helper('peexl_referfriends')->__('Total to customer order from.'),
        ));
        $fieldset->addField('discount_type', 'select', array(
            'label' => Mage::helper('peexl_referfriends')->__('Total Bonuses To'),
            'class' => 'required-entry validate-number',
            'required' => true,
            'name' => 'discount_type',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('peexl_referfriends')->__('Fixed'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('peexl_referfriends')->__('% Percentage'),
                ),
            ),
            'note' => Mage::helper('peexl_referfriends')->__('Discount type(fixed or percentage).'),
        ));

        $fieldset->addField('discount_value', 'text', array(
            'label' => Mage::helper('peexl_referfriends')->__('Discount value'),
            'class' => 'required-entry validate-number validate-greater-than-zero',
            'required' => true,
            'name' => 'discount_value',
        ));

        $form->setValues($data);

        return parent::_prepareForm();
    }

}
