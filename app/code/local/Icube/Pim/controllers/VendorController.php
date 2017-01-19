<?php
class Icube_Pim_VendorController extends Icube_Pim_Controller_PimAbstract
{    
	public function loginAction()
	{	
		if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Login'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();	
	}
	
	public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    if (!$session->login($login['username'], $login['password'])) {
                            $session->addError($this->__('Invalid username or password.'));
                        }        
                } catch (Exception $e) {
                        $session->addError($e->getMessage());
                  	}
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }
	
	protected function _loginPostRedirect()
    {
        $this->_getSession()->loginPostRedirect($this);
    }
	
	public function logoutAction()
    {
        $this->_getSession()->logout();
        $this->_getSession()->addSuccess($this->__('You are Now Logged Out'));
        $this->_redirect('pim/vendor');
    }
	
	public function indexAction()
	{
        $session = $this->_getSession();

        $grandTotalWeek = 0;
        $grandTotalMonth = 0;

        //week date
        $weekDate = new Zend_Date(Mage::getModel('core/date')->timestamp());
        $weekDate->subWeekDay('1');
        $weekDate->toString('Y-m-d');

        //week date
        $monthDate = new Zend_Date(Mage::getModel('core/date')->timestamp());
        $monthDate-> subMonth('1');
        $monthDate->toString('Y-m-d');

        //current date
        $curDate = new Zend_Date(Mage::getModel('core/date')->timestamp());
        $curDate->toString('Y-m-d');

        //calculate week to date
        $salesModel=Mage::getModel("sales/order");
        $salesWeekCollection = $salesModel->getCollection()
                    ->addAttributeToFilter('updated_at', array('from' => $weekDate,'to' => $curDate,'date' => true))
                    ->addAttributeToFilter('vendor_id',$session->getVendorId())
                    ->addFieldToFilter(array('status'),
                       array(
                           array(
                               array('like' => 'complete'),
                               array('like' => 'shipped')
                               )
                           )
                    );

        foreach($salesWeekCollection as $weeklyOrder)
        {
            $orderId= $weeklyOrder->getIncrementId();
            $weeklyOrder = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);
            $grandTotalWeek = $grandTotalWeek + $weeklyOrder->getGrandTotal();
        }

        //calculate Month to date
        $salesMonthCollection = $salesModel->getCollection()
                    ->addAttributeToFilter('updated_at', array('from' => $monthDate,'to' => $curDate,'date' => true))
                    ->addAttributeToFilter('vendor_id',$session->getVendorId())
                    ->addFieldToFilter(array('status'),
                       array(
                           array(
                               array('like' => 'complete'),
                               array('like' => 'shipped')
                               )
                    ));

        foreach($salesMonthCollection as $monthlyOrder)
        {
            $orderId= $monthlyOrder->getIncrementId();
            $monthlyOrder = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);
            $grandTotalMonth = $grandTotalMonth + $monthlyOrder->getGrandTotal();
        }

        //calculate product Uploaded
        $productUploaded = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('vendor_id',array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))->getSize();
        
        //calculate product approved
        $optionId = Mage::helper('pim')->getApprovalOptionId('approved');

        $productApproved = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('vendor_id',array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
                    ->addAttributeToFilter('approval_status', array('eq' => $optionId))->getSize();

        //calculate product Innactive
        $attribute_code = "status";     //Attribute name
        $attribute_option = "2";        //Value for disable

        $attribute_details = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attribute_code); 
        $options = $attribute_details->getSource()->getAllOptions(false); 
        $SelectedOptionId=false;

        foreach($options as $option)
        { 
            if($option["value"]==$attribute_option)
            {
                $SelectedOptionId=$option["value"];
            }
        }

        if($SelectedOptionId)
        {
            $productStatus = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('vendor_id',array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($session->getVendorId())))
                    ->addAttributeToFilter($attribute_code, array('eq'=>$SelectedOptionId))->getSize();
        }

        Mage::register('total_month', $grandTotalMonth);
        Mage::register('total_week', $grandTotalWeek);
        Mage::register('total_upload', $productUploaded);
        Mage::register('total_approved', $productApproved);
        Mage::register('total_inactive', $productStatus);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Dashboard'));
        $this->renderLayout();
	}

    public function passwordAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Reset Password'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();  
    }
	
    public function submitpasswordAction()
    {
        $session = $this->_getSession();
        $post = $this->getRequest()->getPost();

        $model = Mage::getModel('pim/vendor');
        $model->setId($session->getId());

        if(Mage::getModel('core/encryption')->getHash($post['old_password']) == $session->getVendorPassword())
        {
            if($post['new_password'] == $post['conf_password'])
            {
                $model->setPassword(Mage::getModel('core/encryption')->getHash($post['new_password']));
                $model->save();

                $this->_getSession()->logout();
                $this->_getSession()->addSuccess($this->__('Your password has been save, please login with your new password'));
                $this->_redirect('pim/vendor');
            }
            else
            {
                $session->addError($this->__('Your password and confirmation password do not match'));
                $this->_redirect('pim/vendor/password');
            }
            
        }
        else
        {
            $session->addError($this->__('Incorect old password'));
            $this->_redirect('pim/vendor/password');
        }
    }
}