<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ReferFriends_Adminhtml_Referrals_TransactionsController extends Mage_Adminhtml_Controller_Action {

    /*
     * Main function layout for admin bonus transactions grid
     */
    public function indexAction() {
        $this->loadLayout()->_setActiveMenu('peexl_main_menu/peexl_rf_bonus_transactions');
        $this->_addContent($this->getLayout()->createBlock('peexl_referfriends/adminhtml_referrals_transactions'));
        $this->renderLayout();
    }

}
