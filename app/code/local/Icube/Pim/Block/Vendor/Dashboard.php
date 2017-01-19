<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Vendor_Dashboard extends Mage_Core_Block_Template
{
	public function getWeekly()
    {
        return Mage::registry('total_week');
    }
    
    public function getMonthly()
    {
        return Mage::registry('total_month');
    }

    public function getUploaded()
    {
        return Mage::registry('total_upload');
    }

    public function getApproved()
    {
        return Mage::registry('total_approved');
    }
    
    public function getInactive()
    {
        return Mage::registry('total_inactive');
    }
}