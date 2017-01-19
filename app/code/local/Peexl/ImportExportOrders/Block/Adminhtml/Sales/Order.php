<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0.0
 */
class Peexl_ImportExportOrders_Block_Adminhtml_Sales_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'importexportorders';
        $this->_controller = 'adminhtml_sales_order';
        $this->_headerText = Mage::helper('importexportorders')->__('Orders');

        parent::__construct();
        $this->_removeButton('add');


    }
}