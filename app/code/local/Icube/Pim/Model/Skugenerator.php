<?php
	
class Icube_Pim_Model_Skugenerator extends Mage_Core_Model_Abstract
{
	public function _construct()
    {
        parent::_construct();
        $this->_init('pim/skugenerator');
    }    
    
    public function generateSKU($categoryCode)
    {
	    $model = $this->load($categoryCode, 'key');
        
        if($model->getId() == NULL)
        {
	       $model->setKey($categoryCode);
	       $model->save();
	        
        }
 
        // Get The Running Alphabet
        if ($model->getRunningAlpha() == '' && $model->getRunningNumber() < '99')
        {
	        $alpha = '';
        }
		elseif ($model->getRunningAlpha() == '' && $model->getRunningNumber() == '99')
		{
			$alpha = 'A';
		}
		elseif ($model->getRunningAlpha() != '' && $model->getRunningNumber() < '99')
		{
			$alpha = $model->getRunningAlpha();
		}
		else
		{
			$alpha = $model->getRunningAlpha();
			$alpha++;
		}
		
		// Get The Running Number
		if ($model->getRunningNumber() == '00' || $model->getRunningNumber() == '99')
		{
			$number = '01';
		}
		else
		{
			$number = $model->getRunningNumber();
			$number++;
			if($number < 10)
			{
				$number = '0'.$number;
			}
		}
		
		// Save The last increment
		$model->setRunningAlpha($alpha)->setRunningNumber($number)->save();
		
        $sku = $categoryCode.$alpha.$number;
        return $sku;

    }
    
}