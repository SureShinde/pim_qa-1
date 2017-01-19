<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Status
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
        switch ($value){
            case 0:
                $html='<span class="grid-severity-critical"><span>'.Mage::helper('peexl_dailydeals')->__('Disabled').'</span></span>';
                break;
            case 1:
                
                if(strtotime($row->getData('date_start'))>time()){
                    $html='<span class="grid-severity-minor"><span>'.Mage::helper('peexl_dailydeals')->__('Queued').'</span></span>';                    
                }
                 if(strtotime($row->getData('date_start'))<=time() && time()<=strtotime($row->getData('date_end'))){                     
                    $html='<span class="grid-severity-notice"><span>'.Mage::helper('peexl_dailydeals')->__('Running').'</span></span>';                    
                }
                 if(strtotime($row->getData('date_end'))<time()){
                    $html='<span class="grid-severity-major"><span>'.Mage::helper('peexl_dailydeals')->__('Ended').'</span></span>';                    
                }
                break;
        }
        return $html;
    }
}
