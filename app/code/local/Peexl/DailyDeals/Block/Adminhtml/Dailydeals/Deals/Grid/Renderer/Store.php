<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Store
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /*
     * Returns the deal's available stores column values
     */
    public function render(Varien_Object $row)
    {
        $html='';
        $value =  $row->getData($this->getColumn()->getIndex());
        if($value==='0'){
            $html=Mage::helper('peexl_dailydeals')->__('All Store Views');
        }
        else{
            $stores=  explode(',', $value);
            if(count($stores)){
                foreach ($stores as $k=>$storeid){
                    if($k>0) $html.='<br>';
                    $store = Mage::app()->getStore($storeid);
                    $html.=$store->getName();
                    
                }
            }
        }
        return $html;
    }
}

