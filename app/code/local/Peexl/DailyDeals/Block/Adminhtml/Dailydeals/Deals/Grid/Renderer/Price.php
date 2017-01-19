<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Price
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /*
     * Returns the deal's price formated
     * 
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->toCurrency($value);
    }
}

