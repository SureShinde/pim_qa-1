<?php
class Icube_Pim_GeneratorController extends Mage_Core_Controller_Front_Action
{
	public function testAction()
	{
		$sample = '9MA';
        print_r('<pre>');
        print_r('generate SKU for Sample Category '.$sample);
        print_r('<br>');
        
        $sku = Mage::getModel('pim/skugenerator')->generateSKU($sample);
        	         
        print_r('SKU:');
        print_r($sku);
	}
	
	public function generateAction()
	{
		 echo('Start Generate SKU');
		 echo('<br>Please go to klikmro_sku.log to check the result');
		 return Mage::helper('pim/generate')->generateSKU();
	}
	
}