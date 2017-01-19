<?php
 
class Icube_Import_Model_Resource_Mapping_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('import/mapping');
    }
}