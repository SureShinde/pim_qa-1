<?php
class Icube_Pim_Model_Observer
{
    public function checkStatus($observer) {
	    $product = $observer->getProduct();
	    $newStatus = $product->getStatus();
	    $oldStatus = $product->getOrigData('status');
	    if ($newStatus != $oldStatus) {
	        return Mage::helper('pim')->updateMro('status', $newStatus, array($product->getSku()));
	    }
	}

}