<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 2/9/15
 * Time: 12:30 PM
 */

class Peexl_ImportExportOrders_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'import_form',
            'action'  => $this->getUrl('*/*/importPost'),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $fieldset = $form->addFieldset('form_base_fieldset', array('legend' => Mage::helper('importexportorders')->__('Import Orders ')));

        $fieldset->addField('import_orders_file', 'file', array(
            'label'     => Mage::helper('importexportorders')->__('Import Orders File'),
            'name' => 'import_orders_file',
            'value'  => 'Uplaod',
            'disabled' => false,
            'readonly' => true,
            'tabindex' => 1
        ));


        $fieldset2 = $form->addFieldset('form_base_fieldset_2', array('legend' => Mage::helper('importexportorders')->__('Import Orders Settings')));
        $stores=Mage::getSingleton('adminhtml/system_store')
            ->getStoreValuesForForm(false, true);
        unset($stores[0]);
        $fieldset2->addField('select-store-view', 'select', array(
            'name' => 'store_view',
            'label' => Mage::helper('importexportorders')->__('Store View'),
            'title' => Mage::helper('importexportorders')->__('Store View'),
            'required' => true,
            'note' => 'Please select a store view to import associated products.',
            'values' => $stores,
        ));

        $fieldset2->addField('index_import', 'radios', array(
            'label'     => Mage::helper('importexportorders')->__('Order IDs'),
            'class'=>"clear",
            'name'      => 'index_import',
            'separator'=>"<br>",
            'onclick' => "",
            'onchange' => "",
            'value'=>"self",
            'values' => array(
                array('value'=>'self','label'=>' Autogenerate order IDs using current magento options.'),
                array('value'=>'file','label'=>' Import orders using IDs from the file (if such ID is present in the shop a new ID will be generated )'),
            ),
            'disabled' => false,
            'readonly' => false,

        ));

        $fieldset2->addField('invoice_create', 'radios', array(
            'label'     => Mage::helper('importexportorders')->__('Create Order Invoice'),
            'class'=>"clear",
            'name'      => 'invoice_create',
            'separator'=>"<br>",
            'onclick' => "",
            'onchange' => "",
            'value' =>"no",
            'values' => array(
                array('value'=>'no','label'=>' No'),
                array('value'=>'yes','label'=>' Yes'),
            ),
            'disabled' => false,
            'readonly' => false,

        ));

        $fieldset2->addField('shipment_create', 'radios', array(
            'label'     => Mage::helper('importexportorders')->__('Create Order Shipment'),
            'class'=>"clear",
            'name'      => 'shipment_create',
            'separator'=>"<br>",
            'onclick' => "",
            'onchange' => "",
            'value' =>"no",
            'values' => array(
                array('value'=>'no','label'=>' No'),
                array('value'=>'yes','label'=>' Yes'),
            ),
            'disabled' => false,
            'readonly' => false,

        ));


        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}