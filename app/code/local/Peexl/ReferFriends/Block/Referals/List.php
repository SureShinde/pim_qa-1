<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Block_Referals_List extends Mage_Core_Block_Template {

    function __construct() {
        parent::__construct();

        $this->setTemplate('peexl/referfriends/account_referals.phtml');

        $referals = Mage::getModel("customer/customer")->getCollection()
                ->addAttributeToSelect("*")
                ->addFieldToFilter('px_referal_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
        $this->setReferals($referals);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('head')->setTitle(Mage::helper('peexl_referfriends')->__('My Referrals'));
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'referfriends.referas.list.pager')
                ->setCollection($this->getReferals());
        $inviteBlock = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'referfriends.sendemail_form',
            array(
                'template' => 'peexl/referfriends/send_email_form.phtml'
            )

        )->setData('referral_url' ,$this->getReferalRegUrl());

        $this->setChild('pager', $pager);
        $this->setChild('inviteform',$inviteBlock);

        $this->getReferals()->load();
        return $this;
    }



    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }

    public function getReferalRegUrl() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (!$customer->getPxUid()) {
            $uid = md5($customer->getId() . time());
            $customer->setPxUid($uid);
            $customer->save();
        } else {
            $uid = $customer->getPxUid();
        }
        return Mage::helper("customer")->getRegisterUrl() . '?referral=' . $uid;
    }

    public function getBackUrl() {
        return $this->getUrl('customer/account/');
    }

    public function getReferalOrderCount($customer) {
        $totals = Mage::helper('peexl_referfriends')->getCustomerTotals($customer);
        return $totals->getNumOrders();
    }

    public function getTotalOrdersAmount($customer) {
        $totals = Mage::helper('peexl_referfriends')->getCustomerTotals($customer);
        return Mage::helper('core')->currency($totals->getLifetime(), true, false);
    }

    public function getRegisterReferalBonuses() {
        return Mage::helper('peexl_referfriends')->getTotalCustomerReferalRegisterBonuses();
    }

    public function getPurchaseReferalBonuses() {
        return Mage::helper('peexl_referfriends')->getTotalCustomerReferalPurchasesBonuses();
    }

    public function getCustomerSpentBonuses() {
        return Mage::helper('peexl_referfriends')->getCustomerSpentBonuses();
    }

    public function getReferalBonuses($referal) {
        return Mage::helper('peexl_referfriends')->getReferalBonuses($referal);
    }

    public function getBonuses() {
       return Mage::helper('peexl_referfriends')->getBonuses();
    }

    public function getCustomerDiscount() {
        //peexl_rf_bonus_purchase
        $bonuses = $this->getBonuses();
        
        if (Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_purchase', Mage::app()->getStore())) {
            return Mage::helper('core')->currency($bonuses["total"] * Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_eq', Mage::app()->getStore()), true, false);
        }
        else{
            $discount= Mage::helper('peexl_referfriends')->getBonusPctDiscount($bonuses["total"]);
            if ($discount->getDiscountType()==0){
                return Mage::helper('core')->currency($discount->getDiscountValue(), true, false);
            }
            else{
                return $discount->getDiscountValue().'%';
            }
        }
    }

}
