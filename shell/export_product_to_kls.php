<?php
require_once 'abstract.php';
 
class Export_Product_To_Kls extends Mage_Shell_Abstract
{
	protected $_argname = array();
 
	public function __construct() {
		parent::__construct();
 
		// Time limit to infinity
		set_time_limit(0);     
 
		// Get command line argument named "argname"
		// Accepts multiple values (comma separated)
		if($this->getArg('argname')) {
			$this->_argname = array_merge(
				$this->_argname,
				array_map(
					'trim',
					explode(',', $this->getArg('argname'))
				)
			);
		}
	}
 
	// Shell script point of entry
	public function run() {
		try {
			Mage::helper('pim')->exportProductToKLS();
		} catch (Exception $e) {
			Mage::log('error-0-start $e=' . $e->getMessage() . ' @' . now(), false, 'icube_error.log', true);
		}
	}
 
	// Usage instructions
	public function usageHelp()
	{
		return <<<USAGE
Usage:  php -f scriptname.php -- [options]
 
  --argname <argvalue>       Argument description
 
  help                   This help
 
USAGE;
	}
}
// Instantiate
$shell = new Export_Product_To_Kls();
 
// Initiate script
$shell->run();
