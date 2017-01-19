<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tab_Grid_Renderer_Radio
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /*
     * Returns the deal's status acording to the DB value
     * 0-Disabled
     * 1-Enabled
     * 
     * 
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $html='<input type="radio" class="radio" onclick="setProduct('.$value.');" value="'.$value.'" id="product_id_radio_'.$value.'" name="grid_product_id">';
        return $html;
    }
}
