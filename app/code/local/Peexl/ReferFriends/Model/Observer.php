<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Model_Observer {

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    const COOKIE_KEY_SOURCE = 'px_referal_uid';

    /*
     * capture the referral uid
     */

    public function captureReferral(Varien_Event_Observer $observer) {
        $referalID = Mage::app()->getRequest()->getParam('referral', false);
        //var_dump(Mage::getSingleton('customer/session')->getReferalId());die();
        if ($referalID) {
            Mage::getSingleton('customer/session')->setReferral($referalID);
        }
    }

    /*
     * set referral if registration is using referral id
     */

    public function setReferral(Varien_Event_Observer $observer) {

        $customer = $observer->getEvent()->getCustomer();
        $uid = md5($customer->getId() . time());
        $customer->setPxUid($uid);


        if (Mage::getSingleton('customer/session')->getReferral()) {
            $collection = Mage::getModel("customer/customer")->getCollection()->addAttributeToSelect("*")
                    ->addFieldToFilter("px_uid", Mage::getSingleton('customer/session')->getReferral());
            Mage::log('Peexl_ReferFriends - Referal Registration - : ' . $customer->getId() . ' | ' . Mage::getSingleton('customer/session')->getReferral());
            $referal = $collection->getFirstItem();
            $customer->setPxReferalId($referal->getId());

            /* chek if needs to set bonus for referal registartion */
            if (Mage::getStoreConfig('peexl/peexl_rf_registration_group/peexl_rf_registartion', Mage::app()->getStore())) {
                $data = array();
                $bonusValue = Mage::getStoreConfig('peexl/peexl_rf_registration_group/peexl_rf_bonusvalue', Mage::app()->getStore());
                $bonus = Mage::getModel('peexl_referfriends/bonus');
                $data["customer_id"] = $customer->getId();
                $data["referal_id"] = $referal->getId();
                $data["bonus"] = $bonusValue;
                $data["action"] = 'referral_registration_bonus';

                $bonus->setData($data);

                try {
                    $bonus->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                    return;
                }
            }


            Mage::getSingleton('customer/session')->unsRefersral();
        }


        //  Mage::getModel('core/cookie')->delete( self::COOKIE_KEY_SOURCE );
        return $this;
    }

    public function orderPlacedBonus($observer) {

        if (Mage::getStoreConfig('peexl/peexl_rf_purchase_group/peexl_rf_purchase', Mage::app()->getStore())) {

            $order = $observer->getEvent()->getOrder();
            $orderTotal = $order->getGrandTotal() - $order->getShippingAmount() - $order->getShippingTaxAmount();
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
            if ($customer->getPxReferalId()) {
                $ruleBonus = Mage::getModel("peexl_referfriends/rules")
                        ->getCollection()
                        ->addFieldToSelect("bonus")
                        ->addFieldToFilter("total_from", array('lteq' => $orderTotal))
                        ->addFieldToFilter("total_to", array('gteq' => $orderTotal))
                        ->getFirstItem();
                $data = array();

                $bonus = Mage::getModel('peexl_referfriends/bonus');
                $data["customer_id"] = $customer->getId();
                $data["referal_id"] = $customer->getPxReferalId();
                $data["bonus"] = $ruleBonus->getBonus();
                $data["action"] = 'referral_purchase_bonus';
                $data["order_id"] = $order->getId();
                $bonus->setData($data);
                try {
                    $bonus->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                    return;
                }
                Mage::log('Peexl_ReferFriends - Order Registration - : OrderID-' . $order->getId() . ' | Total:' . $orderTotal . ' | CustomerID:' . $order->getCustomerId() . ' | Bonus: ' . $ruleBonus->getBonus());
            }

            if (Mage::getSingleton('checkout/session')->getReferalDiscountSum()) {
                $bonusValue = (int) (Mage::getSingleton('checkout/session')->getReferalDiscountSum() / Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_eq', Mage::app()->getStore()));
                $bonus = Mage::getModel('peexl_referfriends/bonus');
                $data["customer_id"] = $customer->getId();
                $data["referal_id"] = $customer->getPxReferalId();
                $data["bonus"] = -$bonusValue;
                $data["action"] = 'customer_purchase_with_bonus';
                $data["order_id"] = $order->getId();
                $bonus->setData($data);
                try {
                    $bonus->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                    return;
                }
                
                Mage::getSingleton('checkout/session')->unsReferalDiscountSum();
            }
        }
    }

    public function placeBonusBlockAfterCoupon(Varien_Event_Observer $observer) {
        if (Mage::getStoreConfig('peexl/peexl_rf_bonus_purchase_group/peexl_rf_bonus_purchase', Mage::app()->getStore())) {
            if ($observer->getBlock() instanceof Mage_Checkout_Block_Cart_Coupon) {
                $cuponBlock = $observer->getTransport()->getHtml();
                $block = new Peexl_ReferFriends_Block_Checkout_Cart_Discount();

                $observer->getTransport()->setHtml($block->toHtml() . $cuponBlock);
            }
        }
    }

}
