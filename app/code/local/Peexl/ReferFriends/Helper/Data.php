<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Helper_Data extends Mage_Catalog_Helper_Data {

    public function getReferalOrderCount($customer_id) {
        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('customer_id', $customer_id);
        return $orders->count();
    }

    /*
     * function getCustomerRegisteredReferals($customerID)
     *  get number of customer registered referals
     */

    public function getCustomerRegisteredReferals($customerID) {
        return Mage::getModel("peexl_referfriends/bonus")
                        ->getCollection()
                        ->addFieldToFilter("referal_id", $customerID)
                        ->addFieldToFilter("action", 'referral_registration_bonus')
                        ->count();
    }

    /*
     * function  getCustomerReferalPurchases($customerID, $result = 'count')
     *  get the Customer referals purchases  
     * return total amount or number of purchases depends on $result parameters
     */

    public function getCustomerReferalPurchases($customerID, $result = 'count') {
        //get count of referals purchases
        if ($result == 'total') {
            $total = 0;
            $orders = Mage::getModel("peexl_referfriends/bonus")
                    ->getCollection()
                    ->addFieldToSelect("*")
                    ->addFieldToFilter("referal_id", $customerID)
                    ->addFieldToFilter("action", 'referral_purchase_bonus');

            foreach ($orders as $row) {

                $order = Mage::getSingleton('sales/order')->load($row->getOrderId());
                //amount without shipping
                $orderTotal = $order->getGrandTotal() - $order->getShippingAmount() - $order->getShippingTaxAmount();
                $total+=$orderTotal;
            }

            return $total;
        }

        return Mage::getModel("peexl_referfriends/bonus")
                        ->getCollection()
                        ->addFieldToFilter("referal_id", $customerID)
                        ->addFieldToFilter("action", 'referral_purchase_bonus')
                        ->count();
    }

     /*
     * function  getReferalsOrderTotalAmount($customer_id)
     *  get the Customer referals nr of purchases     
     */
    
    public function getReferalsOrderTotalAmount($customer_id) {
        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('customer_id', $customer_id);
        return $orders->count();
    }

    public function getCustomerTotals(Mage_Customer_Model_Customer $customer) {

        return Mage::getResourceModel('sales/sale_collection')
                        ->setOrderStateFilter(Mage_Sales_Model_Order::STATE_CANCELED, true)
                        ->setCustomerFilter($customer)
                        ->load()
                        ->getTotals();
    }
    
    /*
     * function  getTotalCustomerReferalRegisterBonuses($customerID)
     *  get the Customer referals registration bonuses     
     */
    public function getTotalCustomerReferalRegisterBonuses($customerID = false) {
        if (!$customerID) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerID = $customer->getId();
        }
        $bonuses = Mage::getModel("peexl_referfriends/bonus")
                ->getCollection()
                ->addExpressionFieldToSelect('total', 'SUM({{bonus}})', 'bonus')
                ->addFieldToFilter("referal_id", $customerID)
                ->addFieldToFilter("action", "referral_registration_bonus")
                ->getFirstItem();
        return $bonuses->getTotal() ? $bonuses->getTotal() : 0;
    }

    /*
     * function  getTotalCustomerReferalPurchasesBonuses($customerID)
     *  get the Customer referals purchases bonuses     
     */
    
    public function getTotalCustomerReferalPurchasesBonuses($customerID = false) {
        if (!$customerID) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerID = $customer->getId();
        }

        $bonuses = Mage::getModel("peexl_referfriends/bonus")
                ->getCollection()
                ->addExpressionFieldToSelect('total', 'SUM({{bonus}})', 'bonus')
                ->addFieldToFilter("referal_id", $customerID)
                ->addFieldToFilter("action", "referral_purchase_bonus")
                ->getFirstItem();
        return $bonuses->getTotal() ? $bonuses->getTotal() : 0;
    }
    
    /*
     * function  getReferalBonuses($customerID)
     *  get the referal total bonuses     
     */

    public function getReferalBonuses($referal) {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $bonuses = Mage::getModel("peexl_referfriends/bonus")
                ->getCollection()
                ->addExpressionFieldToSelect('total', 'SUM({{bonus}})', 'bonus')
                ->addFieldToFilter("referal_id", $customer->getId())
                ->addFieldToFilter("customer_id", $referal->getId())
                ->getFirstItem();
        return $bonuses->getTotal() ? $bonuses->getTotal() : 0;
    }

    public function getBonusPctDiscount($bonuses) {
        $discount = Mage::getModel("peexl_referfriends/discounts")
                ->getCollection()
                ->addFieldToSelect("*")
                ->addFieldToFilter("total_from", array('lteq' => $bonuses))
                ->addFieldToFilter("total_to", array('gteq' => $bonuses))
                ->getFirstItem();
        return $discount;
    }

    /*
     * function  getCustomerSpentBonuses($customerID)
     *  get the customer spent bonuses     
     */
    public function getCustomerSpentBonuses($customerID = false) {
        if (!$customerID) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerID = $customer->getId();
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $bonuses = Mage::getModel("peexl_referfriends/bonus")
        ->getCollection()
        ->addExpressionFieldToSelect('total', 'SUM({{bonus}})', 'bonus')
        ->addFieldToFilter("customer_id", $customerID)
        ->addFieldToFilter("action", "customer_purchase_with_bonus")
                ->getFirstItem();
        return $bonuses->getTotal() ? $bonuses->getTotal() : 0;
    }

     /*
     * function  getBonuses($customerID)
     *  get the customer bonuses     
      * returns an array with purchaseBonuses,registerBonuses,spentBonuses and total bonuses
     */
    public function getBonuses($customerID=false) {
        $purchaseBonuses = (int) $this->getTotalCustomerReferalPurchasesBonuses($customerID);
        $registerBonuses = (int) $this->getTotalCustomerReferalRegisterBonuses($customerID);
        $spentBonuses = (int) $this->getCustomerSpentBonuses($customerID);

        $total = $purchaseBonuses + $registerBonuses + $spentBonuses;

        return array('purchaseBonuses' => $purchaseBonuses,
            'registerBonuses' => $registerBonuses,
            'spentBonuses' => $spentBonuses,
            'total' => $total);
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

}

?>
