<?php

class Peexl_ProductDesigner_Block_Adminhtml_Graphics_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('graphics_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('productdesigner/graphics')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('productdesigner')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'id',
        ));
 
        $this->addColumn('name', array(
            'header'    => Mage::helper('productdesigner')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
 
        $this->addColumn('image', array(
            'header'    => Mage::helper('productdesigner')->__('Image'),
            'align'     =>'left',
            'index'     => 'image',
            'renderer'  => 'Peexl_ProductDesigner_Block_Adminhtml_Graphics_Renderer'
        ));
        
        /*
        $this->addColumn('price', array(
            'header'    => Mage::helper('productdesigner')->__('Price'),
            'align'     =>'left',
            'index'     => 'price',
        ));
        */  
        
        $this->addColumn('position', array(
            'header'    => Mage::helper('productdesigner')->__('Position'),
            'align'     =>'left',
            'index'     => 'position',
        ));
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}