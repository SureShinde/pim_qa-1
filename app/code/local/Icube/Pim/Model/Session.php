<?php
class Icube_Pim_Model_Session extends Mage_Core_Model_Session_Abstract
{
	protected $_vendor;

    public function __construct()
    {
        $namespace = 'pim';
        $this->init($namespace);
        Mage::dispatchEvent('pim_session_init', array('session'=>$this));
    }
    
    public function setVendor($vendor)
    {
        $this->_vendor = $vendor;
        return $this;
    }

    public function getVendor()
    {
        if ($this->_vendor instanceof Icube_Pim_Model_Vendor) {
            return $this->_vendor;
        }
		
		$vendor = Mage::getModel('pim/vendor');
        if ($this->getId()) {
            $vendor->load($this->getId());
        }
        
        $this->setVendor($vendor);

        return $this->_vendor;
    }
    
    public function getVendorId()
    {
        return $this->getVendor()->getVendorId();
    }
    
	public function isLoggedIn()
    {
        return (bool)$this->getId() && (bool)$this->getVendor()->getId();
    }
    
    public function setVendorAsLoggedIn($vendor)
    {
        $this->setVendor($vendor);
        $this->setId($vendor->getId());
        Mage::dispatchEvent('pim_vendor_login', array('vendor'=>$vendor));
        return $this;
    }
    
    public function login($username, $password)
    {
        $vendor = Mage::getModel('pim/vendor');

        if ($vendor->authenticate($username, $password)) {
            $this->setVendorAsLoggedIn($vendor);
            return true;
        }
        return false;
    }
    
    public function loginById($vendorId)
    {
        $vendor = Mage::getModel('pim/vendor')->load($vendorId);
        if ($vendor->getId()) {
            $this->setVendorAsLoggedIn($vendor);
            return true;
        }
        return false;
    }
    
    public function logout()
    {
        if ($this->isLoggedIn()) {
            $this->setId(null);
            Mage::dispatchEvent('pim_vendor_logout', array('vendor'=>$this->getVendor()));
        }
        return $this;
    }
    
	public function authenticate(Mage_Core_Controller_Varien_Action $action, $loginUrl = null)
    {
        if (!$this->isLoggedIn()) {
            $this->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_current'=>true)));
            if (is_null($loginUrl)) {
                $loginUrl = Mage::getUrl('pim/vendor/login');
            }
            $action->getResponse()->setRedirect($loginUrl);
            return false;
        }
        return true;
    }
    
    public function loginPostRedirect($action)
    {
        if (!$this->getBeforeAuthUrl() || $this->getBeforeAuthUrl() == Mage::getBaseUrl() ) {
            $this->setBeforeAuthUrl(Mage::getUrl('pim/vendor'));
            if ($this->isLoggedIn()) {
                if ($action->getRequest()->getActionName()=='noRoute') {
                    $this->setBeforeAuthUrl(Mage::getUrl('*/*'));
                } else {
                    $this->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_current'=>true)));
                }
                if ($this->getAfterAuthUrl()) {
                    $this->setBeforeAuthUrl($this->getAfterAuthUrl(true));
                }
            }
        } else if ($this->getBeforeAuthUrl() == Mage::getUrl('pim/vendor/logout')) {
            $this->setBeforeAuthUrl(Mage::getUrl('pim/vendor'));
        } else {
            if (!$this->getAfterAuthUrl()) {
                $this->setAfterAuthUrl($this->getBeforeAuthUrl());
            }
            if ($this->isLoggedIn()) {
                $this->setBeforeAuthUrl($this->getAfterAuthUrl(true));
            }
        }
        $action->getResponse()->setRedirect($this->getBeforeAuthUrl(true));
    }  
    
    public function getVendorPassword()
    {
        return $this->getVendor()->getPassword();
    }
}