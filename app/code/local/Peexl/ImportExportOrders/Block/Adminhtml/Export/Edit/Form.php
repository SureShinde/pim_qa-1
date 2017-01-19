<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 2/9/15
 * Time: 12:30 PM
 */

class Peexl_ImportExportOrders_Block_Adminhtml_Export_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'export_form',
            'action'  => $this->getUrl('*/*/exportPost'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $fieldset = $form->addFieldset('form_base_fieldset', array('legend' => Mage::helper('importexportorders')->__('Export Orders Settings')));

        $stores=Mage::getSingleton('adminhtml/system_store')
            ->getStoreValuesForForm(false, true);
        unset($stores[0]);
        $fieldset->addField('select-store-view', 'select', array(
            'name' => 'store_view',
            'label' => Mage::helper('importexportorders')->__('Store View'),
            'title' => Mage::helper('importexportorders')->__('Store View'),
            'required' => true,
            'note' => 'Please select a store view to import associated products.',
            'values' => $stores,
        ));

        $fieldset->addField('orders_per_file', 'text', array(
            'name' => 'orders_per_file',
            'label' => Mage::helper('importexportorders')->__('Orders Conunt'),
            'title' => Mage::helper('importexportorders')->__('Orders Conunt'),
            'required' => true,
            'note' => 'Orders per file ( recommended value : 100 )',
            'value' => '100',
        ));



        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}