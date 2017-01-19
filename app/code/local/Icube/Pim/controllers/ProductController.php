<?php
class Icube_Pim_ProductController extends Icube_Pim_Controller_PimAbstract
{
	public function uploadAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Product Upload'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function uploadsubmitAction()
	{
		$session = $this->_getSession();
		if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '')
	    {
		    $oriFilename = $_FILES['filename']['name'];
	      	$profileTitle = 'Vendor Product Import';
	    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
			// file name should be $vendor_id + $oriFilename
		 	$location_path = Mage::getBaseDir('var').'/urapidflow/import/'.$session->getVendorId();    
            Mage::getConfig()->createDirIfNotExists($location_path);
            $model_unirgy->setBaseDir($location_path);
		 	try
		 	{
			 	$uploader = new Varien_File_Uploader('filename');
		        $uploader->setAllowedExtensions('csv');
		        $uploader->setAllowRenameFiles(true);
		        $uploader->setFilesDispersion(false);
	            $uploaderLocation = $uploader->save($location_path, $oriFilename);

	            $csv = new Varien_File_Csv();
                $csv->setDelimiter(',');
                $data = $csv->getData($uploaderLocation['path'].DS.$uploaderLocation['file']);
				
				$keyVendor = array_search('vendor_sku', $data[0]);
				if(!$keyVendor)
				{
					$session->addError("Empty Vendor SKU");
					return;
				}

				$keySku = array_search('sku', $data[0]);
				if($keySku === false)
				{ $keySku = array_push($data[0],'sku'); }

				$keyVendorId = array_search('vendor_id', $data[0]);
				if($keyVendorId === false)
				{ $keyVendorId = array_push($data[0],'vendor_id'); }
				
				$keyKlikSku = array_search('klikmro_sku', $data[0]);
				if($keyKlikSku === false)
				{ $keyKlikSku = array_push($data[0],'klikmro_sku'); }
				
				$keyPrice = array_search('price', $data[0]);
				if($keyPrice === false)
				{ $keyPrice = array_push($data[0],'price'); }
				
				$keyIds = array_search('category.ids', $data[0]);
				if($keyIds === false)
				{ $keyIds = array_push($data[0],'category.ids'); }
				
				$keySet = array_search('product.attribute_set', $data[0]);
				if($keySet === false)
				{ $keySet = array_push($data[0],'product.attribute_set'); }
 
                foreach ($data as $key => $row)
                {
	                if($key != 0)
	                {
		                $newSku = $session->getVendorId().'-'.$row[$keyVendor];
						$data[$key][$keySku] = $newSku;
						$data[$key][$keyVendorId] = $session->getVendorId();
						$data[$key][$keyKlikSku] = 'klikmro_sku';
						$data[$key][$keyPrice] = 0;
						
						$model = Mage::getModel('import/mapping')->load($row[$keySet], 'ah_code');
						if($model->getAttributeSet())
				        {
				          $data[$key][$keySet] = $model->getAttributeSet();
				        }
				        else
				        {
					        $data[$key][$keySet] = 'Attribute 11';
				        }
						
						$category = Mage::getModel('catalog/category')
				        ->getCollection()
				        ->addAttributeToFilter('name', $model->getDefaultCategory())
				        ->getFirstItem();
						
						if($category->getId() == NULL)
						{
							// Set Default Category when AH does not exist
							$category = Mage::getModel('catalog/category')
					        ->getCollection()
					        ->addAttributeToFilter('name', 'TC Burrs Sets')
					        ->getFirstItem();
						}
						
						$ids = str_replace('/',';',$category->getData('path'));
			            $data[$key][$keyIds] = $ids;
	                }
	            }

				$csv->saveData($uploaderLocation['path'].DS.$uploaderLocation['file'], $data);
				$session->addSuccess("Product Successfully Uploaded");

			 	$model_unirgy->setFilename($uploaderLocation['file']);
				$model_unirgy->start()->save()->run();

				$session->addSuccess("Rows Success: ".$model_unirgy->getRowsSuccess());
				//$session->addError("Errors: ".$model_unirgy->getNumErrors());
				//$session->addWarning("Warning: ".$model_unirgy->getNumWarnings());
				$this->notifyAdmin($session->getVendorId(),'New product(s) uploaded');

				$this->_redirect('pim/product/list');
				return;
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}

		}
		else
		{
			$session->addError("Please select the File");
		}

		$this->_redirectReferer();
	}

	public function updatepriceAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Update Price'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function updatepricesubmitAction()
	{
		$session = $this->_getSession();
		if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '')
	    {
	    	$oriFilename = $_FILES['filename']['name'];
	      	$profileTitle = 'Vendor Price Update';
	    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');

		 	// file name should be $vendor_id + $oriFilename
		 	$location_path = Mage::getBaseDir('var').'/urapidflow/import/'.$session->getVendorId();    
            Mage::getConfig()->createDirIfNotExists($location_path);
            $model_unirgy->setBaseDir($location_path);
		 	try
		 	{
		 		$uploader = new Varien_File_Uploader('filename');
		        $uploader->setAllowedExtensions('csv');
		        $uploader->setAllowRenameFiles(true);
		        $uploader->setFilesDispersion(false);

	            $uploaderLocation = $uploader->save($location_path, $oriFilename);

	            $csv = new Varien_File_Csv();
                $csv->setDelimiter(',');
                $data = $csv->getData($uploaderLocation['path'].DS.$uploaderLocation['file']);
                $i = 0;
                if(count($data[0]) == 4 && strcmp($data[0][0],'vendor_sku')==0 && strcmp($data[0][1],'msrp')==0 && strcmp($data[0][2],'suggested_price')==0 && strcmp($data[0][3],'special_price')==0) {
									$optionId = Mage::helper('pim')->getApprovalOptionId('not approved');
									foreach ($data as $row)
	                {
		                if($i == 0)
		                {
			                array_push($data[$i],"sku");
											array_push($data[$i],"approval_status");
		                }
						else
						{
							$product = Mage::getModel('catalog/product')->getCollection()
								->addAttributeToSelect('sku')
								->addAttributeToFilter('vendor_sku', $row[0])
								->addAttributeToFilter('vendor_id',
	                                array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
								->getFirstItem();
							if (!$product->getId()) {unset($data[$i]);}
							else {
								array_push($data[$i],$product->getSku());
		                        $product->setApprovalStatus($optionId)->save();
							}
						}
						$i++;
		            }

					$csv->saveData($uploaderLocation['path'].DS.$uploaderLocation['file'], $data);
					$session->addSuccess("Product Successfully Updated");

				 	$model_unirgy->setFilename($uploaderLocation['file']);
					$model_unirgy->start()->save()->run();

					$session->addSuccess("Rows Success: ".$model_unirgy->getRowsSuccess());
					$this->notifyAdmin($session->getVendorId(),'New price(s) uploaded');
				}
				else {
					$session->addSuccess('The header of the csv file must be: vendor_sku, price, msrp, special_price, suggested_price');
				}
				//$session->addError("Errors: ".$model_unirgy->getNumErrors());
				//$session->addWarning("Warning: ".$model_unirgy->getNumWarnings());
				$this->_redirect('pim/product/list');
				return;
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}

		}
		else
		{
			$session->addError("Please select the File");
		}

		$this->_redirectReferer();
	}

	public function updatestockAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Update Stock'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function updatestocksubmitAction()
	{
		$session = $this->_getSession();
		if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '')
	    {
	    	$oriFilename = $_FILES['filename']['name'];
	      	$profileTitle = 'Vendor Stock Update';
	    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');

		 	// file name should be $vendor_id + $oriFilename
		 	$location_path = Mage::getBaseDir('var').'/urapidflow/import/'.$session->getVendorId();    
            Mage::getConfig()->createDirIfNotExists($location_path);
            $model_unirgy->setBaseDir($location_path);
		 	try
		 	{
		 		$uploader = new Varien_File_Uploader('filename');
		        $uploader->setAllowedExtensions('csv');
		        $uploader->setAllowRenameFiles(true);
		        $uploader->setFilesDispersion(false);

		        // After the vendor ID ready, file name should be $vendor_id + $oriFilename
	            $uploaderLocation = $uploader->save($location_path, $oriFilename);
	            // var_dump($uploaderLocation);die;

	            $csv = new Varien_File_Csv();
                $csv->setDelimiter(',');
                // var_dump($uploaderLocation['path'].DS.$uploaderLocation['file']);die;
                $data = $csv->getData($uploaderLocation['path'].DS.$uploaderLocation['file']);
                $i = 0;
                $array_stock = array();
                if(count($data[0]) == 2 && strcmp($data[0][0],'vendor_sku')==0 && strcmp($data[0][1],'stock.qty')==0 ) {
	                foreach ($data as $row)
	                {
		                if($i == 0)
		                {
			                array_push($data[$i],"sku");
		                }
						else
						{
							$product = Mage::getModel('catalog/product')->getCollection()
								->addAttributeToSelect('sku')
								->addAttributeToSelect('klikmro_sku')
								->addAttributeToFilter('vendor_sku', $row[0])
								->addAttributeToFilter('vendor_id',
									array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
	                            ->getFirstItem();
							if (!$product->getId()) {unset($data[$i]);}
							else {
								array_push($data[$i],$product->getSku());
								$array_stock[$product->getKlikmroSku()] = $row[1];
							}
						}
						$i++;
		            }

		            $csv->saveData($uploaderLocation['path'].DS.$uploaderLocation['file'], $data);
					$session->addSuccess("Product Successfully Updated");

				 	$model_unirgy->setFilename($uploaderLocation['file']);
					$model_unirgy->start()->save()->run();

					Mage::helper('pim')->updateMro('stock', null, $array_stock);
					$this->notifyAdmin($session->getVendorId(),'New stock(s) uploaded');

					$session->addSuccess("Rows Success: ".$model_unirgy->getRowsSuccess());
				}
				else {
					$session->addSuccess('The header of the csv file must be: vendor_sku, stock.qty');
				}

				//$session->addError("Errors: ".$model_unirgy->getNumErrors());
				//$session->addWarning("Warning: ".$model_unirgy->getNumWarnings());
				$this->_redirect('pim/product/list');
				return;
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}

		}
		else
		{
			$session->addError("Please select the File");
		}

		$this->_redirectReferer();
	}

	public function updateimageAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Update Stock'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function updateimagesubmitAction()
	{
		$session = $this->_getSession();
		if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '')
	    {
	    	$oriFilename = $_FILES['filename']['name'];
	      	$profileTitle = 'Vendor Image Upload';
	    	$model_unirgy = Mage::getModel('urapidflow/profile')->load($profileTitle, 'title');
			// file name should be $vendor_id + $oriFilename
		 	$location_path = Mage::getBaseDir('var').'/urapidflow/import/'.$session->getVendorId();    
            Mage::getConfig()->createDirIfNotExists($location_path);
            $model_unirgy->setBaseDir($location_path);
		 	try
		 	{
		 		$uploader = new Varien_File_Uploader('filename');
		        $uploader->setAllowedExtensions('csv');
		        $uploader->setAllowRenameFiles(true);
		        $uploader->setFilesDispersion(false);

		        // After the vendor ID ready, file name should be $vendor_id + $oriFilename
	            $uploaderLocation = $uploader->save($location_path, $oriFilename);

	            $csv = new Varien_File_Csv();
                $csv->setDelimiter(',');

                $data = $csv->getData($uploaderLocation['path'].DS.$uploaderLocation['file']);
                $i = 0;
                if(count($data[0]) == 2 && strcmp($data[0][0],'vendor_sku')==0 && strcmp($data[0][1],'image')==0 ) {
	                foreach ($data as $row)
	                {
		                if($i == 0)
		                {
			                array_push($data[$i],"sku");
		                }
						else
						{
							$product = Mage::getModel('catalog/product')->getCollection()
								->addAttributeToSelect('sku')
								->addAttributeToFilter('vendor_sku', $row[0])
								->addAttributeToFilter('vendor_id',
	                                array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
	                            ->getFirstItem();
							if (!$product->getId()) {unset($data[$i]);}
							else {
								array_push($data[$i],$product->getSku());
								$data[$i][1] = DS.$data[$i][1];
							}
						}
						$i++;
		            }

		            $csv->saveData($uploaderLocation['path'].DS.$uploaderLocation['file'], $data);
					$session->addSuccess("Product Successfully Updated");

				 	$model_unirgy->setFilename($uploaderLocation['file']);
					$model_unirgy->start()->save()->run();

					$session->addSuccess("Rows Success: ".$model_unirgy->getRowsSuccess());
					$this->notifyAdmin($session->getVendorId(),'New image(s) uploaded');
				}
				else {
					$session->addSuccess('The header of the csv file must be: vendor_sku, image');
				}
				//$session->addError("Errors: ".$model_unirgy->getNumErrors());
				//$session->addWarning("Warning: ".$model_unirgy->getNumWarnings());
				$this->_redirect('pim/product/list');
				return;
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}

		}
		else
		{
			$session->addError("Please select the File");
		}

		$this->_redirectReferer();
	}

	public function listAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Product List'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function editAction()
	{
		$session = $this->_getSession();
		$id = (int) $this->getRequest()->getParam('id');

		$product = Mage::getModel('catalog/product')->getCollection()
							->addAttributeToSelect('*')
							->addAttributeToFilter('entity_id', $id)
							->addAttributeToFilter('vendor_id',
                                array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
                            ->getFirstItem();

		if (!$product->getId())
		{
            $session->addError($this->__('Product is not found.'));
            $this->_redirect('pim/product/list');
            return;
        }

        Mage::register('vendor_product', $product);

		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Product Edit'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function deleteAction()
	{
		$session = $this->_getSession();
		$id = (int) $this->getRequest()->getParam('id');

		$product = Mage::getModel('catalog/product')->getCollection()
							->addAttributeToSelect('*')
							->addAttributeToFilter('entity_id', $id)
							->addAttributeToFilter('vendor_id',
                                array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
                            ->getFirstItem();

		if (!$product->getId())
		{
            $session->addError($this->__('Product is not found.'));
            $this->_redirect('pim/product/list');
            return;
        }
        else if($product->getData('vendor_id') != Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId()))
        {
            $session->addError('Product does not belongs to this vendor');
			return;
        }

        Mage::register("isSecureArea", true); 

		$product->delete();

		$this->_redirectUrl(Mage::getUrl('pim/product/list/'));

	}

	public function editsubmitAction()
	{
		$session = $this->_getSession();
		if (!$this->getRequest()->isPost()) {
            $this->_redirectError(Mage::getUrl('*/*/list'));
            return;
        }
        try
        {
        	$postData = $this->getRequest()->getPost();
            /* @var $product Mage_Catalog_Model_Product */
            $product = Mage::getModel('catalog/product');
            $product->setData('_edit_mode', true);
            $product->setStoreId(Mage::app()->getStore()->getId());

            if ($productId = $postData['product_id']) {
                $product->load($productId);
            }

            if($product->getData('vendor_id') != Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId()))
            {
	            $session->addError('Product does not belongs to this vendor');
				return;
            }
			
			$oldStoreId = Mage::app()->getStore()->getId();
            $dateFields = array();
            $attributes = $product->getAttributes();
            foreach ($attributes as $attrKey => $attribute) {
                if ($attribute->getBackend()->getType() == 'datetime') {
                    if (array_key_exists($attrKey, $postData) && $postData[$attrKey] != ''){
                        $dateFields[] = $attrKey;
                    }
                }
            }
            $postData = $this->_filterDates($postData, $dateFields);

            // Not Approved status after edit
            $postData['approval_status'] =  Mage::helper('pim')->getApprovalOptionId('not approved');
            $product->addData($postData);

            /* set restrictions for date ranges */
            $resource = $product->getResource();
            $resource->getAttribute('special_from_date')
                ->setMaxValue($product->getSpecialToDate());
            $resource->getAttribute('news_from_date')
                ->setMaxValue($product->getNewsToDate());
            $resource->getAttribute('custom_design_from')
                ->setMaxValue($product->getCustomDesignTo());
			
			Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            $product->validate();
            $product->save();
            Mage::app()->setCurrentStore($oldStoreId);
            $session->addSuccess('The product has been saved.');
        }
		catch (Mage_Eav_Model_Entity_Attribute_Exception $e) {
            $session->addError($e->getMessage());
        }
        catch (Exception $e) {
            $session->addError($e->getMessage());
        }

        $this->_redirectUrl(Mage::getUrl('pim/product/edit/', array('id' =>  $postData['product_id'])));

	}

	public function notifyAdmin($vendorId, $description)
	{
		$vendor = Mage::getModel('pim/vendor')->load($vendorId, 'vendor_id');

        $emailTemplate  = Mage::getModel('core/email_template')
                        ->loadDefault('sales_icube_adminNotifier_email_template');                                 

		$emailTemplate->setSenderName($vendor->getVendorName());
		$emailTemplate->setSenderEmail($vendor->getEmail());
		$emailTemplateVariables = array(
					'vendor_name' => $vendor->getVendorName(),
		            'vendor_id'	=> $vendorId,
		            'description'      => $description);
		$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
		$emailTemplate->send(Mage::getStoreConfig('trans_email/ident_notif/email',Mage::app()->getStore()),'Admin Notifier', $emailTemplateVariables,$storeId=null);

	}
}
