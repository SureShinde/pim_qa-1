<?php

class Peexl_ReferFriends_Block_Checkout_Cart_Discount extends Mage_Checkout_Block_Cart_Abstract {

    function __construct() {
        // return '';
        parent::__construct();
        $this->setTemplate('peexl/referfriends/checkout/cart/discount.phtml');
        //Mage::log('Refer friends block discount log');
    }

    function isSetReferalDiscount() {
        if (!Mage::getSingleton('checkout/session')->getReferalDiscountSum()) {
            return false;
        }

        return true;
    }

    function getRferalDiscountSum() {
        if (!Mage::getSingleton('checkout/session')->getReferalDiscountSum()) {
            return false;
        } else {
            return Mage::helper('core')->currency(Mage::getSingleton('checkout/session')->getReferalDiscountSum(), true, false);
        }
    }

    function getAvailableBonusWithoutCurrency() {
        $bonuses = Mage::helper('peexl_referfriends')->getBonuses();
        if (Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_purchase', Mage::app()->getStore())) {
            return $bonuses["total"] * Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_eq', Mage::app()->getStore());
        }
        return 0;
        ;
    }

    function getAvailableBonus() {
        $bonuses = Mage::helper('peexl_referfriends')->getBonuses();
        if (Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_purchase', Mage::app()->getStore())) {
            return Mage::helper('core')->currency($bonuses["total"] * Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_eq', Mage::app()->getStore()), true, false);
        }
        return Mage::helper('core')->currency(0, true, false);
        ;
    }

}
