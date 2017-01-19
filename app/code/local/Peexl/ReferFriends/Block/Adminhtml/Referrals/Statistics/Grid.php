<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('customer_bonus_statistics');
        $this->setDefaultSort('id');
    }

    protected function _prepareCollection() {

        $collection = Mage::getModel('peexl_referfriends/bonus')
                ->getCollection()
                ->addFieldToSelect("referal_id")
                ->distinct(true)
                ->addFieldToFilter('referal_id', array('gt' => 0));
        ;
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns() {
        $this->addColumn('referal_id', array(
            'header' => Mage::helper('peexl_referfriends')->__('Customer'),
            'sortable' => true,
//            'width'=>'250px',
            'index' => 'referal_id',
            'type' => 'text',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_Customer'
        ));

        $this->addColumn('registered_referals', array(
            'header' => Mage::helper('peexl_referfriends')->__('Registered referrals'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_RegReferalNumber'
        ));
        $this->addColumn('referals_p_qty', array(
            'header' => Mage::helper('peexl_referfriends')->__('Nr. Of Referrals Purchases'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'referal_id',
            'type' => 'text',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_ReferalNrPurchases'
        ));
//        
        $this->addColumn('referals_p_amount', array(
            'header' => Mage::helper('peexl_referfriends')->__('Amount Of Referrals Purchases'),
            'sortable' => true,
            'width' => '80px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_ReferalAmountPurchases'
        ));

        $this->addColumn('referals_registartion_bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Bonuses from registration'),
            'sortable' => true,
            'width' => '80px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_ReferalRegBonuses'
        ));

        $this->addColumn('referals_purchase_bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Bonuses from referral purchases'),
            'sortable' => true,
            'width' => '80px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_ReferalPurchaseBonuses'
        ));

        $this->addColumn('customer_spent_bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Customer spent Bonuses'),
            'sortable' => true,
            'width' => '80px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_CustomerSpentBonuses'
        ));
        
        $this->addColumn('customer_available_bonus', array(
            'header' => Mage::helper('peexl_referfriends')->__('Customer available Bonuses'),
            'sortable' => true,
            'width' => '80px',
            'index' => 'referal_id',
            'type' => 'number',
            'renderer' => 'Peexl_ReferFriends_Block_Adminhtml_Referrals_Statistics_Grid_Renderer_CustomerAvailableBonuses'
        ));
        return parent::_prepareColumns();
    }

}
