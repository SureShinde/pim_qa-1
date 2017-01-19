<?php
class Icube_External_Model_Resource_Ftpd extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('external/ftpd', 'User');
    }
}