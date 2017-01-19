<?php 
class Icube_Pim_Model_Mysql4_Vendor extends Mage_Core_Model_Mysql4_Abstract
{
    
	public function _construct()
	{    
	    $this->_init('pim/vendor','id');
	}   
    
}
