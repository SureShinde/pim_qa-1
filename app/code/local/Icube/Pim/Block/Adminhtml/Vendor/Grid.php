<?php
 
class Icube_Pim_Block_Adminhtml_Vendor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('vendor_grid');
        $this->setDefaultSort('vendor_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('pim/vendor')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('vendor_id', array(
            'header'    => Mage::helper('pim')->__('Vendor ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'vendor_id',
        ));
 
        $this->addColumn('vendor_name', array(
            'header'    => Mage::helper('pim')->__('Vendor Name'),
            'align'     =>'left',
            'index'     => 'vendor_name',
        ));
        
        $this->addColumn('sales_person', array(
            'header'    => Mage::helper('pim')->__('Sales Person'),
            'align'     =>'left',
            'index'     => 'salesperson',
        ));
        
        $this->addColumn('email', array(
            'header'    => Mage::helper('pim')->__('Email'),
            'align'     =>'left',
            'index'     => 'email',
        ));
        
        $this->addColumn('status', array(
            'header'    => Mage::helper('pim')->__('Status'),
            'align'     =>'left',
            'index'     => 'status',
			'type'		=>'options',
            'options' => array('A' => 'Active', 'I' => 'Inactive')
            ));
 
        return parent::_prepareColumns();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
}