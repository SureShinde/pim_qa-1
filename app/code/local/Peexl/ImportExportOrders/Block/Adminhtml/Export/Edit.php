<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0.0
 */
class Peexl_ImportExportOrders_Block_Adminhtml_Export_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->removeButton('back')
            ->removeButton('reset')
            ->removeButton('save');

        $this->_addButton('export_orders', array(
            'label'     => Mage::helper('adminhtml')->__('Export Orders'),
            'onclick'   => 'sendExportRequest(1,0,false,\'no\')',
            'class'     => 'save',
        ), -100);

        $this->_addButton('export_orders_xls', array(
            'label'     => Mage::helper('adminhtml')->__('Export To XLS'),
            'onclick'   => 'sendExportRequest(1,0,false,\'yes\')',
            'class'     => 'save',
        ), -100);

    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId   = 'export_id';
        $this->_blockGroup = 'importexportorders';
        $this->_controller = 'adminhtml_export';
    }

    /**
     * Get header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('importexportorders')->__('Export');
    }
}
