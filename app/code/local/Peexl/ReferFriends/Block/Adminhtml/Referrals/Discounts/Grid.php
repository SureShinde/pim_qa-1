<?php
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_bonus_discounts');
        $this->setDefaultSort('id');
    }
 
 
    protected function _prepareCollection()
    {
 
        $collection = Mage::getModel('peexl_referfriends/discounts')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
 
    protected function _prepareColumns()
    {
        $this->addColumn('total_from', array(
            'header' => Mage::helper('peexl_referfriends')->__('Bonus Amount FROM'),
            'sortable' => true,
            'width' => '60',
            'index' => 'total_from',
            'type'  => 'text'
        ));
 
        $this->addColumn('total_to', array(
            'header' => Mage::helper('peexl_referfriends')->__('Bonus Amount To'),
            'sortable' => true,
            'width' => '60',
            'index' => 'total_to',
            'type'  => 'text'
        ));
        $this->addColumn('discount_value', array(
            'header' => Mage::helper('peexl_referfriends')->__('Discount value'),
            'sortable' => true,
            'width' => '60',
            'index' => 'discount_value',
            'type'  => 'text',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Discounts_Grid_Renderer_Discount'
        ));
 
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    protected function _prepareMassaction()
    {
       $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('peexl_referfriends_discounts');
 
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('peexl_referfriends')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('peexl_referfriends')->__('Are you sure?')
        ));

        return $this;
    }
 
}