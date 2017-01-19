<?php
 
class Icube_Import_Model_Mapping extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('import/mapping');
    }
}