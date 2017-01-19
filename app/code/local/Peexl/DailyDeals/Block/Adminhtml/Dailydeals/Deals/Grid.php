<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('deals');
        $this->setDefaultSort('id');
    }
 
 
    protected function _prepareCollection()
    {
 
        $collection = Mage::getModel('peexl_dailydeals/deals')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
 
    protected function _prepareColumns()
    {
         $this->addColumn('id', array(
            'header' => Mage::helper('peexl_dailydeals')->__('ID'),
            'sortable' => true,
            'width' => '20',
            'index' => 'id',
            'type'  => 'text',            
        ));
        $this->addColumn('product', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Product'),
            'sortable' => true,
            'width' => '160',
            'index' => 'product_id',
            'type'  => 'text',
             'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Product'
        ));
        $this->addColumn('sku', array(
            'header' => Mage::helper('peexl_dailydeals')->__('SKU'),
            'sortable' => true,
            'width' => '60',
            'index' => 'product_id',
            'type'  => 'text',
             'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_SKU'
        ));
        
         $this->addColumn('store_id', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Stores'),
            'sortable' => true,
            'width' => '160',
            'index' => 'store_id',
            'type'  => 'text',  
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Store'
        ));
        
         $this->addColumn('deal_price', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Deal Price'),
            'sortable' => true,
            'width' => '60',
            'index' => 'deal_price',
            'type'  => 'price',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Price'
        ));
        $this->addColumn('deal_qty', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Deal Qty'),
            'sortable' => true,
            'width' => '60',
            'index' => 'deal_qty',
            'type'  => 'number'
        ));
        $this->addColumn('deal_sales_qty', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Sales Qty'),
            'sortable' => true,
            'width' => '60',
            'index' => 'id',
            'type'  => 'number',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_SalesQty'
        ));
        $this->addColumn('date_start', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Date Start'),
            'sortable' => true,
            'width' => '50',
            'index' => 'date_start',
            'type'  => 'datetime',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Datetime'
        ));
 
       
        $this->addColumn('date_end', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Date End'),
            'sortable' => true,
            'width' => '50',
            'index' => 'date_end',
            'type'  => 'datetime',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Datetime'
        ));
       
        $this->addColumn('deal_views', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Nr. Views'),
            'sortable' => true,
            'width' => '60',
            'index' => 'deal_views',
            'type'  => 'number'
        ));
        
        $this->addColumn('deal_status', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Status'),
            'sortable' => true,
            'width' => '60',
            'index' => 'deal_status',
            'type'  => 'text',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Grid_Renderer_Status'
        ));
        
        
        $this->addColumn('deal_position', array(
            'header' => Mage::helper('peexl_dailydeals')->__('Position'),
            'sortable' => true,
            'width' => '60',
            'index' => 'deal_position',
            'type'  => 'text'
        ));
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        $page=Mage::helper('peexl_dailydeals')->getDealProductGridPage($row->getProductId());
        return $this->getUrl('*/*/edit', array('id' => $row->getId(),'page'=>$page));         
//        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    protected function _prepareMassaction()
    {
       $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('peexl_dailydeals_deals');
 
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('peexl_dailydeals')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('peexl_dailydeals')->__('Are you sure?')
        ));

        return $this;
    }
 
}