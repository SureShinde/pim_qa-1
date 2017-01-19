<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Adminhtml_Dailydeals_DealsController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout()->_setActiveMenu('peexl_dailydeals_menu/peexl_dailydeals_deals');
        $this->_addContent($this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('peexl_dailydeals/deals');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_dailydeals')->__('Deal does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('px_dailydeals_deal_data', $model);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals_edit'))
                ->_addLeft($this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals_edit_tabs'));
        $this->renderLayout();
    }

    public function saveAction() {
        $dealData=array();
        if ($data = $this->getRequest()->getPost()) {
            $dealData["product_id"]=$data["product_id"];
            $dealData["deal_price"]=$data["deal_price"];
            $dealData["deal_qty"]=$data["deal_qty"];
            
            $dealData["date_start"]=$data['date_start'];
            $dealData["date_end"]=$data['date_end'];
            $dealData["deal_status"]=$data['deal_status'];
            if(isset($data['stores'])) {
                if(in_array('0',$data['stores'])){
                    $dealData['store_id'] = '0';
                }
                else{
                    $dealData['store_id'] = implode(",", $data['stores']);
                }
               unset($data['stores']);
            }
            
            Mage::log($dealData);
            $model = Mage::getModel('peexl_dailydeals/deals');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            $model->setData($dealData);

            Mage::getSingleton('adminhtml/session')->setFormData($dealData);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();

                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('peexl_dailydeals')->__('Error saving deal'));
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('peexl_dailydeals')->__('Deal was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                
                Mage::log($model->getData());

                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }

            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_dailydeals')->__('No data found to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $rule = Mage::getModel('peexl_dailydeals/deals');
                $rule->setId($id);
                $rule->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('peexl_dailydeals')->__('The deal has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find the rule to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $rulesIds = $this->getRequest()->getParam('peexl_dailydeals_deals');
        if (!is_array($rulesIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_dailydeals')->__('Please select deal(s).'));
        } else {
            try {
                $rule = Mage::getModel('peexl_dailydeals/deals');
                foreach ($rulesIds as $ruleId) {
                    $rule->setId($ruleId)
                            ->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__('Total of %d record(s) were deleted.', count($rulesIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function prodgridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody( $this->getLayout()->createBlock('peexl_dailydeals/adminhtml_dailydeals_deals_edit_tab_products')->toHtml());
    }

}
