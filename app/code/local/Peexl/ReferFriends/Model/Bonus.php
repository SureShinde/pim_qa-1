<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Model_Bonus extends Mage_Core_Model_Abstract {

    protected function _construct() {
        parent::_construct();
        $this->_init('peexl_referfriends/bonus');
    }

}