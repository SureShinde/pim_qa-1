<?php
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Rules_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_bonus_rules');
        $this->setDefaultSort('id');
    }
 
 
    protected function _prepareCollection()
    {
 
        $collection = Mage::getModel('peexl_referfriends/rules')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
 
    protected function _prepareColumns()
    {
        $this->addColumn('total_from', array(
            'header' => Mage::helper('peexl_referfriends')->__('Total FROM'),
            'sortable' => true,
            'width' => '60',
            'index' => 'total_from',
            'type'  => 'text'
        ));
 
        $this->addColumn('total_to', array(
            'header' => Mage::helper('peexl_referfriends')->__('Total To'),
            'sortable' => true,
            'width' => '60',
            'index' => 'total_to',
            'type'  => 'text'
        ));
        $this->addColumn('bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Bonus'),
            'sortable' => true,
            'width' => '60',
            'index' => 'bonus',
            'type'  => 'text'
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
        $this->getMassactionBlock()->setFormFieldName('peexl_referfriends_rules');
 
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('peexl_referfriends')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('peexl_referfriends')->__('Are you sure?')
        ));

        return $this;
    }
 
}