<?php
 
class Icube_Pim_Adminhtml_VendorController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
        $this->_title($this->__('Pim'))->_title($this->__('Manage Vendor'));
        $this->loadLayout();
        $this->_setActiveMenu('pim/pim');
        $this->_addContent($this->getLayout()->createBlock('pim/adminhtml_vendor'));
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    public function editAction()
    {   
        $id     = $this->getRequest()->getParam('id',null);
        $model  = Mage::getModel('pim/vendor');
 
        if ($id) {
            $model->load((int) $id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('pim')->__('Vendor does not exist'));
                $this->_redirect('*/*/');
            }
            
        }
        $this->_title($model->getId() ? $model->getVendorName() : $this->__('New Vendor'));

        Mage::register('vendor_data', $model);

        $this->loadLayout();
       
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
       
        $this->_addContent($this->getLayout()->createBlock('pim/adminhtml_vendor_edit'));
           
        $this->renderLayout();
    }
    
    public function saveAction()
    {
        if ( $post = $this->getRequest()->getPost() )
        {
	        $id = $this->getRequest()->getParam('id');
            try {
	            $post['id'] = $id;
	            $valid = Mage::helper('pim')->validate($post);
	            
				if($valid == '')
				{					
					$model = Mage::getModel('pim/vendor');
					$model->setId($id)
						->setVendorId($post['vendor_id'])
						->setVendorName($post['vendor_name'])
						->setEmail($post['email'])
						->setSalesperson($post['salesperson'])
						->setSearchterm($post['searchterm'])
						->setStreet($post['street'])
						->setCity($post['city'])
						->setZip($post['zip'])
						->setCountryId($post['country_id'])
						->setTelephone($post['telephone'])
						->setRegion($post['region'])
						->setStatus($post['status']);
					
					if($post['new_password'] != '')
					{
						$model->setPassword(Mage::getModel('core/encryption')->getHash($post['new_password']));
					}
	                $model->save();

                    $stat = 0;
                    if($post['status'] == 'A'){
                        $stat = 1;
                    }
                    $ftpd = Mage::getModel('external/ftpd')->load($post['vendor_id']);
                    $resource = Mage::getSingleton('core/resource');
                    $readConnection = $resource->getConnection('external_read');
                    $writeConnection = $resource->getConnection('external_write');
                    if ($readConnection->isConnected() && $writeConnection->isConnected()){
                        if(is_null($ftpd->getData('User'))){
                            $password = md5($post['new_password']);
                            $writeConnection->insert(
                                    "ftpd", 
                                    array("User" => $post['vendor_id'], "Company" => $post['vendor_name'], "Status" => $stat, "Password" => $password, "Dir" =>'/var/public/pim.klikmro.com/current/var/urapidflow/import/'.$post['vendor_id'].'/images')
                            );
                        }
                        else {
                            if($post['new_password'] != '')
                            {
                                $password = md5($post['new_password']);
                                $writeConnection->update(
                                        "ftpd",
                                        array("Company" => $post['vendor_name'], "Status" => $stat, "Password" => $password),
                                        "User=".$post['vendor_id']
                                );
                            }
                            else
                            {
                                $writeConnection->update(
                                        "ftpd",
                                        array("Company" => $post['vendor_name'], "Status" => $stat),
                                        "User=".$post['vendor_id']
                                );
                            }
                            
                        }
                    }

                    $mailer = Mage::getModel('core/email_template_mailer');
                    $emailInfo = Mage::getModel('core/email_info');
                    $emailInfo->addTo($post['email'], $post['vendor_name']);
                    $mailer->addEmailInfo($emailInfo);
                    $mailer->setSender(Mage::getStoreConfig('sales_email/order_comment/identity', 0));
                    $mailer->setStoreId(0);
                    $mailer->setTemplateId('sales_icube_newVendorNotifier_email_template');
                    $mailer->setTemplateParams(array(
                        'vendor_name' => $post['vendor_name'],
                        'vendor_id'      => $post['vendor_id'],
                        'vendor_password' => $post['new_password'],
                        'base_url'    => Mage::getBaseUrl(),
                    ));

                    $mailer->send();
	
	                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Vendor was successfully saved'));
	                Mage::getSingleton('adminhtml/session')->setVendorData(false);
	 
	                $this->_redirect('*/*/');
	                return;	
				}
				else
				{
					Mage::getSingleton('adminhtml/session')->addError($valid);
	                Mage::getSingleton('adminhtml/session')->setVendorData($post);
	                if($id){ $this->_redirect('*/*/edit', array('id' => $id)); }
	                else{ $this->_redirect('*/*/new'); }
	                
	                return;
				}
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setVendorData($post);
                if($id){ $this->_redirect('*/*/edit', array('id' => $id)); }
	            else{ $this->_redirect('*/*/new'); }
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('pim/pim');

    }
    
    public function gridAction() {
    	$this->loadLayout()->renderLayout();
    	$this->getResponse()->setBody($this->getLayout()->createBlock('pim/adminhtml_vendor_grid')->toHtml());
	}
}