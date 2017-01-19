<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
/*
 * Shows the main content of the referals page from customer account page
 * 
 */

class Peexl_ReferFriends_MainController extends Mage_Core_Controller_Front_Action
{


    protected function _goBack()
    {
        $returnUrl = $this->getRequest()->getParam('return_url');
        if ($returnUrl) {

            if (!$this->_isUrlInternal($returnUrl)) {
                throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');
            }

            $this->_getSession()->getMessages(true);
            $this->getResponse()->setRedirect($returnUrl);
        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart') && !$this->getRequest()->getParam('in_cart') && $backUrl = $this->_getRefererUrl()
        ) {
            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    public function indexAction()
    {


        $this->loadLayout();

        $this->renderLayout();
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    public function sendmailAction(){

        $templateId = Mage::getStoreConfig('peexl/peexl_rf_invitation_form_group/peexl_rf_transational_email_id', Mage::app()->getStore());

// Set sender information
        $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
        $sender = array('name' => $senderName,
            'email' => $senderEmail);

// Set recepient information
        $recepientEmail = $this->getRequest()->getParam('email');
        $recepientName = $this->getRequest()->getParam('name');

// Get Store ID
        $store = Mage::app()->getStore()->getId();

// Set variables that can be used in email template
        $vars = array('referal_url_link' => Mage::helper('peexl_referfriends')->getReferalRegUrl(),
                      'customer_text' => $this->getRequest()->getParam('message_text'));

        $translate  = Mage::getSingleton('core/translate');

// Send Transactional Email
        Mage::getModel('core/email_template')
            ->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $store);

        $translate->setTranslateInline(true);


        echo json_encode(array('mail'=>'sent'));
    }




}
