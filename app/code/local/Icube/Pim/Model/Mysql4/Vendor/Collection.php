<?php
class Icube_Pim_Model_Mysql4_Vendor_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

	public function _construct()
	{
		$this->_init('pim/vendor');
	}

}