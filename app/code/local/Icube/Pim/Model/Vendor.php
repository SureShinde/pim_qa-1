<?php
	
class Icube_Pim_Model_Vendor extends Mage_Core_Model_Abstract
{
	public function _construct()
    {
        parent::_construct();
        $this->_init('pim/vendor');
    }
    
    public function authenticate($username, $password)
    {
        $collection = $this->getCollection();
        $where = 'vendor_id=:username';
        $order = array(new Zend_Db_Expr('vendor_id=:username desc'));

        $collection->getSelect()
            ->where("status = 'A'")
            ->where($where)
            ->order($order);
        $collection->addBindParam('username', $username);
        foreach ($collection as $candidate) {
            if (!Mage::helper('core')->validateHash($password, $candidate->getPassword())) {
                continue;
            }
            $this->load($candidate->getId());
            //$this->checkConfirmation();
            return true;
        }
        if (($firstFound = $collection->getFirstItem()) && $firstFound->getId()) {
            $this->load($firstFound->getId());
            if (!$this->getId()) {
                $this->unsetData();
                return false;
            }
        }
        return false;
    }

    
}