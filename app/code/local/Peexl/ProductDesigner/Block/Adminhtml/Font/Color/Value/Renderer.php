<?php

class Peexl_ProductDesigner_Block_Adminhtml_Font_Color_Value_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $value =  $row->getData($this->getColumn()->getIndex());
        return '<span style="float: left; width: 55px; text-transform: uppercase;">' . $value . '</span>' . '<div style="background:'.$value.'; float: left; width:15px; height: 15px; border: 1px solid black;"></div>';
    }
}