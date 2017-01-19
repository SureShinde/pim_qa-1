<?php

/**
 * @category    Peexl
 * @package     Peexl_ProductDesigner
 * @author      Peexl Web Development
 * @copyright   Copyright (c) 2013 Peexl Web Development (http://www.peexl.com)
 */
class Peexl_ProductDesigner_Model_Adminhtml_System_Config_Source {

    public function getDesignerTypes() {
        return Mage::helper('productdesigner')->getDesignerTypes();
    }
}
