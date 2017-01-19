<?php
class Icube_Pim_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function validate($post)
	{
		$message = '';
		$vendors = Mage::getModel('pim/vendor')->getCollection();
		foreach($vendors as $vendor)
		{
			if($vendor->getVendorId() == $post['vendor_id'] && $vendor->getId() != $post['id'])
			{
				$message = 'Vendor ID already exists';
				break;
			}
			/*
			// Not Allowed duplicate Email
			if($vendor->getEmail() == $post['email'] && $vendor->getId() != $post['id'])
			{
				$message = 'Email already exists';
				break;
			}
			*/
		}

		return $message;
	}

    public function getVendorAttributeOptionId($vendorid)
	{
		$id = Mage::getResourceModel('catalog/product')
                            ->getAttribute('vendor_id')
                            ->getSource()
                            ->getOptionId($vendorid);
        return $id;
	}

	public function getApprovalOptionId($option)
{
	$id = Mage::getResourceModel('catalog/product')
									->getAttribute('approval_status')
									->getSource()
									->getOptionId($option);
			return $id;
}

    public function getOrderById($id)
	{
		$session = Mage::getSingleton('pim/session');
		$order = Mage::getModel('sales/order')->getCollection()
							->addFieldToSelect('*')
							->addFieldToFilter('entity_id', $id)
							->addAttributeToFilter('vendor_id',
                                array('eq' => $session->getVendorId()))
                            ->getFirstItem();
        return $order;

	}

	public function exportProductToMro()
    {
		$profileTitle = 'Export Product to MRO';
        $model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
        $location_path = Mage::getConfig()->getVarDir('urapidflow/export/pim');

        try{
            $filename = 'pim-product_'.date('Y-m-d-h-i-s').'.csv';
            $model_unirgy->setFilename($filename);
            $model_unirgy->setBaseDir($location_path);
            $model_unirgy->start()->save()->run();
            $result = "export data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
			$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
			$result .= ". Warnings: ".$model_unirgy->getNumWarnings();
			echo $result;
			Mage::log($result,null,"export.log");
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }


    public function exportProductToKLS()
    {
        $profileTitle = 'Export product To KLS';
        $model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
        $location_path = Mage::getConfig()->getVarDir('urapidflow/export/pimkls');
        try{
            $filename = 'pimkls-product_'.date('Y-m-d-h-i-s').'.csv';
            $model_unirgy->setFilename($filename);
            $model_unirgy->setBaseDir($location_path);
            $model_unirgy->start()->save()->run();
            $result = "export data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
            $result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
            $result .= ". Warnings: ".$model_unirgy->getNumWarnings();
            echo $result;
            Mage::log($result,null,"export.log");
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }


    
    public function exportProductImageToMro()
    {
		$profileTitle = 'Export Product Image to MRO';
        $model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
        $location_path = Mage::getConfig()->getVarDir('urapidflow/export/pim');

        try{
            $filename = 'pim-product-image_'.date('Y-m-d-h-i-s').'.csv';
            $model_unirgy->setFilename($filename);
            $model_unirgy->setBaseDir($location_path);
            $model_unirgy->start()->save()->run();
            $result = "export data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
			$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
			$result .= ". Warnings: ".$model_unirgy->getNumWarnings();
			echo $result;
			Mage::log($result,null,"export.log");
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function exportProductGalleryToMro()
    {
		$profileTitle = 'Export Product Gallery to MRO';
        $model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
        $location_path = Mage::getConfig()->getVarDir('urapidflow/export/pim');

        try{
            $filename = 'pim-product-gallery_'.date('Y-m-d-h-i-s').'.csv';
            $model_unirgy->setFilename($filename);
            $model_unirgy->setBaseDir($location_path);
            $model_unirgy->start()->save()->run();
            $result = "export gallery data from ".$filename." completed. Run status: ".$model_unirgy->getRunStatus();
			$result .= ". Rows Success: ".$model_unirgy->getRowsSuccess().". Errors: ".$model_unirgy->getNumErrors();
			$result .= ". Warnings: ".$model_unirgy->getNumWarnings();
			echo $result;
			Mage::log($result,null,"export.log");
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function updateMro($type, $status, $arrData) {
    	$data = array('type' => $type, 'status' => $status, 'arrData' => $arrData);

    	$url = Mage::getStoreConfig('icube_product/mro_site/url',Mage::app()->getStore());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); //timeout after 300 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.json_encode($data));
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        $result=curl_exec($ch);
        curl_close($ch);
        if($result) Mage::log($result,null,'mro-update.log');
    }
        
}
