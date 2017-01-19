<?php

class Peexl_ProductCustomAttributes_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select extends
Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Select {

    public function __construct() {
        parent::__construct();         
        $this->setTemplate('peexl_productcustomattributes/select.phtml');
        $this->setCanEditPrice(true);
        $this->setCanReadPrice(true);   
        // Mage::log('Peexl_ProductCustomAttributes_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select',false, 'ProductCustomAttributes.log');     

    }

    public function _prepareLayout() {
        $this->setChild('add_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add New Row'),
                            'class' => 'add add-select-row',
                            'id' => 'add_select_row_button_{{option_id}}',
                        ))
        );

        $this->setChild('delete_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Delete Row'),
                            'class' => 'delete delete-select-row icon-btn',
                        ))
        );

        $this->setChild('add_image_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add Image'),
                            'class' => 'add',
                            'id' => 'new-option-file-{{id}}-{{select_id}}',
                            'onclick' => 'selectOptionType.addUploadFile(this.id)'
        )));
        return parent::_prepareLayout();
    }

    public function getAddButtonHtml() {
        return $this->getChildHtml('add_select_row_button');
    }

   

    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_select_row_button');
    }

    public function getAddImageButtonHtml() {
        return $this->getChildHtml('add_image_button');
    }





}