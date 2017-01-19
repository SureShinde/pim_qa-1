<?php

class Peexl_ReferFriends_Model_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    protected  $_code="referal_discount";
    public function collect(Mage_Sales_Model_Quote_Address $address) {

        $quote = $address->getQuote();
        if ($address->getData('address_type') == 'billing')
            return $this;
        $grandTotal = $address->getGrandTotal();
        $baseGrandTotal = $address->getBaseGrandTotal();

        $totals = array_sum($address->getAllTotalAmounts());
        $baseTotals = array_sum($address->getAllBaseTotalAmounts());
        $bonuses = Mage::helper('peexl_referfriends')->getBonuses();
        
        //  $discount = 10;

        if (Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_purchase', Mage::app()->getStore())) {
            if (!Mage::getSingleton('checkout/session')->getReferalDiscountSum()) {
                
                    if (Mage::app()->getRequest()->getParam('discount_sum')) {
                        $discount = Mage::app()->getRequest()->getParam('discount_sum');
                        Mage::log('Refer log - ' . Mage::app()->getRequest()->getParams());
                        $address->setFeeAmount(-$discount);
                        $address->setBaseFeeAmount(-$discount);
                        $quote->setFeeAmount($discount);
                        Mage::getSingleton('checkout/session')->setReferalDiscountSum($discount);
                    }
                
            } else {
                if (Mage::app()->getRequest()->getParam('remove-rf-discount') && Mage::app()->getRequest()->getParam('remove-rf-discount') == 1) {
                 
                    $address->unsFeeAmount();
                    $address->unsBaseFeeAmount();
                    $quote->unsFeeAmount($discount);
                    Mage::getSingleton('checkout/session')->unsReferalDiscountSum();
        
                } 
                else{
                    $address->setFeeAmount(-Mage::getSingleton('checkout/session')->getReferalDiscountSum());
                    $address->setBaseFeeAmount(-Mage::getSingleton('checkout/session')->getReferalDiscountSum());
                    $quote->setFeeAmount(-Mage::getSingleton('checkout/session')->getReferalDiscountSum());
                 }
                
            }
        } else {
            $discount = Mage::helper('peexl_referfriends')->getBonusPctDiscount($bonuses["total"]);
            if ($discount->getDiscountType() == 0) {
                $address->setFeeAmount(-$discount->getDiscountValue());
                $address->setBaseFeeAmount(-$discount->getDiscountValue());
                $quote->setFeeAmount(-$discount->getDiscountValue());
            } else {
                $address->setFeeAmount(-$totals * $discount->getDiscountValue() / 100);
                $address->setBaseFeeAmount(-$baseTotals * $discount->getDiscountValue() / 100);
                $quote->setFeeAmount(-$totals * $discount->getDiscountValue() / 100);
            }
        }


        $address->setGrandTotal($grandTotal + $address->getFeeAmount());
        $address->setBaseGrandTotal($baseGrandTotal + $address->getBaseFeeAmount());

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {

        if ($address->getData('address_type') == 'billing')
            return $this;

        $amt = $address->getFeeAmount();
        Mage::log($this->getCode());
        if ($amt != 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => 'Referral Discount',
                'value' => $amt
            ));
        }
        return $address;
    }

}

?>