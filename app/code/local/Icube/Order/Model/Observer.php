<?php
class Icube_Order_Model_Observer
{
    public function saveVendorId(Varien_Event_Observer $observer)
    {
            $shipment = $observer->getEvent()->getShipment();
            if(!$shipment->getVendorId()){
	            $order = $shipment->getOrder();

	            $vendorid = $order->getVendorId();
	            $shipment->setVendorId($vendorid)->save();
            }
    }

}