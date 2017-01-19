<?php
 
class Icube_Import_Model_Resource_Mapping extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('import/mapping', 'id');
    }
}