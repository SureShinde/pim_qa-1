<?php
class Icube_Pim_Helper_Tracking extends Mage_Core_Helper_Abstract
{
	public function getCarriers()
    {
        $carriers = array();
        $carrierInstances = Mage::getSingleton('shipping/config')->getAllCarriers(
            Mage::app()->getStore()->getStoreId()
        );
        $carriers['custom'] = Mage::helper('pim')->__('Custom Value');
        foreach ($carrierInstances as $code => $carrier) {
            if ($carrier->isTrackingAvailable()) {
                $carriers[$code] = $carrier->getConfigData('title');
            }
        }
        return $carriers;
    }
}