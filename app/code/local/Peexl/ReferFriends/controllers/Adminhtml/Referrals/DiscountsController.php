<?php

class Peexl_ReferFriends_Adminhtml_Referrals_DiscountsController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $this->loadLayout()->_setActiveMenu('peexl_main_menu/peexl_rf_discounts');
        $this->_addContent($this->getLayout()->createBlock('peexl_referfriends/adminhtml_referrals_discounts'));
        $this->renderLayout();
    }
    
    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('peexl_referfriends/discounts');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_referfriends')->__('Discount does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('px_referfriends_discount_data', $model);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('peexl_referfriends/discounts');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            $model->setData($data);

            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();

                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('peexl_referfriends')->__('Error saving discount'));
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('peexl_referfriends')->__('Discount was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_referfriends')->__('No data found to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $rule = Mage::getModel('peexl_referfriends/discounts');
                $rule->setId($id);
                $rule->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('peexl_referfriends')->__('The discount has been deleted.'));
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
        $rulesIds = $this->getRequest()->getParam('peexl_referfriends_discounts');
        if (!is_array($rulesIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('peexl_referfriends')->__('Please select discount(s).'));
        } else {
            try {
                $rule = Mage::getModel('peexl_referfriends/discounts');
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
}