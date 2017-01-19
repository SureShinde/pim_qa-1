<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Shipment_Tracking extends Mage_Core_Block_Template
{
	public function getCarriers()
    {
        return  Mage::helper('pim/tracking')->getCarriers();
    }
    
    public function getOrder()
    {
        return Mage::registry('current_order');
    }
    
}