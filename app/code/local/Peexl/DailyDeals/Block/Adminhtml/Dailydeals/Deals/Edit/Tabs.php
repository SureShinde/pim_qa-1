<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
/**
* Constructor
*/
public function __construct()
{
parent::__construct();
$this->setId('edit_deal_tabs');
$this->setDestElementId('edit_form');
$this->setTitle(Mage::helper('peexl_dailydeals')->__('Deal Information'));
}
 
/**
* add tabs before output
*
* @return Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tabs
*/
protected function _beforeToHtml()
{
$this->addTab('general', array(
'label' => Mage::helper('peexl_dailydeals')->__('General'),
'title' => Mage::helper('peexl_dailydeals')->__('General'),
'content' => $this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals_edit_tab_general')->toHtml(),
));
$this->addTab('products', array(
'label' => Mage::helper('peexl_dailydeals')->__('Select Product'),
'title' => Mage::helper('peexl_dailydeals')->__('Select Product'),
'content' => $this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals_edit_tab_products')->toHtml(),
));
return parent::_beforeToHtml();
}
 
}

