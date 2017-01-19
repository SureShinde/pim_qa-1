<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_SKU
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /*
     * Returns the product's SKU from the deals grid
     */
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        $model = Mage::getModel('catalog/product'); //getting product model 
        $_product = $model->load($value);
        $html=$_product->getSku();
        return $html;
    }
}