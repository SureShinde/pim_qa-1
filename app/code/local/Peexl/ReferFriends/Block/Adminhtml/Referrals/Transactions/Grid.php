<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */

class Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_bonus_transactions');
        $this->setDefaultSort('id');
    }
 
 
    protected function _prepareCollection()
    {
 
        $collection = Mage::getModel('peexl_referfriends/bonus')->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
 
 
    protected function _prepareColumns()
    {
        $this->addColumn('referal_id', array(
            'header' => Mage::helper('peexl_referfriends')->__('Customer'),
            'sortable' => true,  
//            'width'=>'250px',
            'index' => 'referal_id',
            'type'  => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid_Renderer_Customer'
        ));
 
        $this->addColumn('bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Transaction Bonuses'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'bonus',
            'type'  => 'number'
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('peexl_referfriends')->__('Description'),
            'sortable' => true,            
            'index' => 'action',
            'type'  => 'text',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid_Renderer_Description'
        ));
        
        $this->addColumn('logdate', array(
            'header' => Mage::helper('peexl_referfriends')->__('Created At'),
            'sortable' => true,
            'width' => '150px',
            'index' => 'logdate',
            'type'  => 'datetime',
           // 'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Transactions_Grid_Renderer_Description'
        ));
 
        return parent::_prepareColumns();
    }
 
   
 
}