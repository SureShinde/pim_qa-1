<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Block_Adminhtml_Product_Edit_Tab_Designer extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function __construct() {
        parent::__construct();
        $this->setProductId($this->getRequest()->getParam('id'));
        $this->setTemplate('productdesigner/product/edit/tab/designer.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('enable_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Enable'),
                            //'class' => 'add',
                            'id' => 'enable_button',
                            'style' => $this->isEnabled()?'display:none;':''
                        ))
        );
        $this->setChild('disable_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Disable'),
                            //'class' => 'delete',
                            'id' => 'disable_button',
                            'style' => $this->isEnabled()?'':'display:none;'
                        ))
        );

        $this->setChild('options_box', 
                        $this->getLayout()
                             ->createBlock('productdesigner/adminhtml_product_edit_tab_designer_options',
                                           'adminhtml.product.edit.tab.designer.options')
        );

        return parent::_prepareLayout();
    }

    public function getOptionsBoxHtml() {
        return $this->getChildHtml('options_box');
    }

    public function getEnableButtonHtml() {
        return $this->getChildHtml('enable_button');
    }

    public function getDisableButtonHtml() {
        return $this->getChildHtml('disable_button');
    }
    
    public function getTabClass() {
        return 'ajax';
    }

    public function isReadonly() {
        return $this->_getProduct()->getCompositeReadonly();
    }

    protected function _getProduct() {
        return Mage::registry('current_product');
    }

    public function getTabLabel() {
        return Mage::helper('productdesigner')->__('Designer');
    }

    public function getTabTitle() {
        return Mage::helper('productdesigner')->__('Designer');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }
    
    public function isEnabled() {
        return $this->_getProduct()->getDesignerEnabled();
    }
    
    public function getFontsXml() {
        $file = Mage::getBaseDir() . '/designer/xml/fonts.xml';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
    }
    
    public function getColorsXml() {
        $file = Mage::getBaseDir() . '/designer/xml/inkColors.xml';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
    }

}