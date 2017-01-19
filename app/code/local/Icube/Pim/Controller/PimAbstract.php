<?php

class Icube_Pim_Controller_PimAbstract extends Mage_Core_Controller_Front_Action
{
	
	protected function _getSession()
    {
        return Mage::getSingleton('pim/session');
    }
    
    public function preDispatch()
    {
        // a brute-force protection here would be nice

        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = strtolower($this->getRequest()->getActionName());
        $openActions = array(
            'login',
            'logout'
        );
        $pattern = '/^(' . implode('|', $openActions) . ')/i';

        if (!preg_match($pattern, $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
    }
    
    public function postDispatch()
    {
        parent::postDispatch();
        $this->_getSession()->unsNoReferer(false);
    }
    
    public function sampleCsvFormatAction()
    {
	    $type = $this->getRequest()->getParam('type');
        $filename = $type.'.csv';
        
        switch($type)
        {
            case 'price' :
            	$content = "vendor_sku,msrp,suggested_price,special_price"."\n"."vendor SKU,11000,12000,11000";
            break;
            
            case 'stock' :
            	$content = "vendor_sku,stock.qty"."\n"."vendor SKU,99";
            break;
            
            case 'image' :
            	$content = "vendor_sku,image"."\n"."vendor SKU,"."imagefilename.jpg";
            break;
            
            default :
                    $content =  '';
            break;
        }
 
        $this->_prepareDownloadResponse($filename, $content);
    }
	
}