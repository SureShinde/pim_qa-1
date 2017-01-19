<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Block_Adminhtml_Product_Edit_Tab_Designer_Options extends Mage_Adminhtml_Block_Widget {

    protected $_product;
    protected $_productInstance;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('productdesigner/product/edit/tab/designer/options.phtml');
    }

    protected function _prepareLayout() {
        $this->setChild('add_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add New Row'),
                            'class' => 'add add-select-row',
                            'id' => 'add_select_row_button_{{id}}'
                        ))
        );

        $this->setChild('delete_select_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Delete Row'),
                            'class' => 'delete delete-select-row icon-btn',
                            'id' => 'delete_select_row_button'
                        ))
        );

        $this->setChild('add_subselect_row_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add New Row'),
                            'class' => 'add add-select-row add-subselect-row',
                            'id' => 'add_select_row_button_{{id}}_{{value_id}}'
                        ))
        );

        return parent::_prepareLayout();
    }

    public function getAddButtonHtml() {
        return $this->getChildHtml('add_select_row_button');
    }

    public function getSubAddButtonHtml() {
        return $this->getChildHtml('add_subselect_row_button');
    }

    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_select_row_button');
    }

    public function getFieldId() {
        return 'package_item';
    }

    public function getFieldName() {
        return 'package_items';
    }

    public function getShowItemsButton() {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'id' => $this->getFieldId() . '_{{index}}_add_button',
                            'label' => Mage::helper('catalog')->__('Add Selection'),
                            'on_click' => 'packageSelection.showSearch(event)',
                            'class' => 'add'
                        ))->toHtml();
    }

    public function getRemoveButton() {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Delete Item'),
                            'class' => 'delete delete-product-item',
                            'on_click' => 'packageItem.remove(event)'
                        ))->toHtml();
    }

    public function getItems() {
        return Mage::registry('current_product')->getTypeInstance()->getItems();
    }

    public function getAddButtonId() {
        //$buttonId = $this->getLayout()
        //                ->getBlock('adminhtml.product.edit.tab.designer')
        //                ->getChild('add_button')->getId();
        $buttonId = 'add_new_item';
        return $buttonId;
    }

    /**
     * Check block is readonly
     *
     * @return boolean
     */
    public function isReadonly() {
        return $this->getProduct()->getOptionsReadonly();
    }

    /**
     * Get Product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct() {
        if (!$this->_productInstance) {
            if ($product = Mage::registry('product')) {
                $this->_productInstance = $product;
            } else {
                $this->_productInstance = Mage::getSingleton('catalog/product');
            }
        }

        return $this->_productInstance;
    }

    public function getOptions() {
        $productId = $this->getProduct()->getId();
        $options = array();
        $_collection = Mage::getModel('productdesigner/option')->getCollection();
        foreach ($_collection as $_option) {
            $option = array();
            $option['title'] = str_replace('{{designer_path}}', Mage::getBaseDir() . '/designer/', $_option->getOptionTitle());
            $option['name'] = $_option->getNameLabel();
            $option['value'] = $_option->getValueLabel();
            $option['code'] = $_option->getOptionCode();
            $option['id'] = $_option->getId();

            $_valueCollection = Mage::getModel('productdesigner/option_value')->getCollection()
                    ->addFieldToFilter('product_id', $productId)
                    ->addFieldToFilter('option_id', $_option->getId());

            $option['values'] = array();
            foreach ($_valueCollection as $_value) {
                $v = unserialize($_value->getValue());
                $v = array_merge($v, array('value_id' => $_value->getId(),
                    'option_id' => $_option->getId(),
                    'option_code' => $_option->getOptionCode()));
                $imagePath = Mage::helper('productdesigner')->getImagesBasePath() . $v['value'];
                if (file_exists($imagePath)) {
                    $v['image_url'] = Mage::helper('productdesigner')->getImagesBaseUrl() . $v['value'];
                    if (strstr($v['value'], '.svg') !== false) {
                        $xmlget = simplexml_load_file($imagePath);
                        $xmlattributes = $xmlget->attributes();
                        $v['width'] = str_replace('px', '', (string) $xmlattributes->width);
                        $v['height'] = str_replace('px', '', (string) $xmlattributes->height);
                    } else {
                        list($v['width'], $v['height']) = getimagesize($imagePath);
                    }
                }
                $option['values'][] = $v;
            }

            $options[] = new Varien_Object($option);
        }

        return $options;
    }

    public function isEnabled() {
        return $this->getProduct()->getDesignerEnabled();
    }

}