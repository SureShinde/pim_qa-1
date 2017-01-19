<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */

class Peexl_DailyDeals_IndexController extends Mage_Core_Controller_Front_Action {
     public function indexAction() {         
        $this->loadLayout();      
        $this->renderLayout();
    }
}