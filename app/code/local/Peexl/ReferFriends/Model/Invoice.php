<?php
class Peexl_ReferFriends_Model_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
         $order = $invoice->getOrder();
        $invoice->setGrandTotal($invoice->getGrandTotal() + $order->getFeeAmount());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $order->getBaseFeeAmount());
 
        return $this;
    }
}

