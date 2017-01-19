<?php

class Peexl_ProductDesigner_Block_Adminhtml_Graphics_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value =  $row->getData($this->getColumn()->getIndex());
        return '<a href="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $value . '" onclick="imagePreview(\'image_' . $row->getId() . '\'); return false;"><img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $value . '" style="max-width: 50px; max-height: 50px;" id="image_' . $row->getId() . '"></a>';
    }
}