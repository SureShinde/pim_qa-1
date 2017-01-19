<?php

class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        if (Mage::getSingleton('adminhtml/session')->getDailydealsData()) {
            $data = Mage::getSingleton('adminhtml/session')->getDailydealsData();
            Mage::getSingleton('adminhtml/session')->getDailydealsData(null);
        } elseif (Mage::registry('px_dailydeals_deal_data')) {
            $data = Mage::registry('px_dailydeals_deal_data')->getData();
        } else {
            $data = array();
        }

        $form = new Varien_Data_Form();

        $this->setForm($form);

        $fieldset = $form->addFieldset('peexl_dailydeals_deal_form_general', array(
            'legend' => Mage::helper('peexl_dailydeals')->__('Deal Settings')
        ));

        
        $fieldset->addField('deal_price', 'text', array(
            'label' => Mage::helper('peexl_dailydeals')->__('Deal Price'),
            'class' => 'required-entry validate-number validate-greater-than-zero',
            'required' => true,
            'name' => 'deal_price',
        ));

        $fieldset->addField('deal_qty', 'text', array(
            'label' => Mage::helper('peexl_dailydeals')->__('Deal Qty'),
            'class' => 'required-entry validate-number validate-greater-than-zero',
            'required' => true,
            'name' => 'deal_qty',
        ));

        $fieldset->addField('date_start', 'date', array(
            'label' => Mage::helper('peexl_dailydeals')->__('Deal Start'),
            'class' => 'required-entry',
            'required' => true,
            'name'=>'date_start',
            'time' => true,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),            
            'format' => 'yyyy-MM-dd H:mm:ss',
            'style'=>'width:200px',
        ));

        $fieldset->addField('date_end', 'date', array(
            'label' => Mage::helper('peexl_dailydeals')->__('Deal End'),
            'class' => 'required-entry',
            'required' => true,
            'time' => true,
            'name' => 'date_end',
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            //'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
            'format' => 'yyyy-MM-dd H:mm:ss',
            'style'=>'width:200px',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('peexl_dailydeals')->__('Store'),
                'title' => Mage::helper('peexl_dailydeals')->__('Store'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }


        $fieldset->addField('deal_status', 'select', array(
            'label' => Mage::helper('peexl_dailydeals')->__('Deal Status'),
            //'class' => 'required-entry',
            'required' => true,
            'name' => 'deal_status',
            'value' => '1',
            'values' => array('0' => Mage::helper('peexl_dailydeals')->__('Disabled'), '1' => Mage::helper('peexl_dailydeals')->__('Enabled')),
        ));



        $form->setValues($data);

        return parent::_prepareForm();
    }

}
