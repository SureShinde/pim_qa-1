<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Block_Adminhtml_Sales_Order_View_Items_Renderer extends Mage_Adminhtml_Block_Sales_Order_View_Items_Renderer_Default {

    public function getImage() {
        $designId = $this->getItem()->getBuyRequest()->getDesignId();
        if($designId) {
            $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
            $value = unserialize($design->getInfo());
            return Mage::getBaseUrl('media') . 'productdesigner/results/' . $value['result'];
        }
    }
}