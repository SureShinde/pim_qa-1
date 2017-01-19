<?php

class Peexl_ProductDesigner_Adminhtml_ColorController extends Mage_Adminhtml_Controller_Action
{
 
    public function indexAction()
    {     
        $this->loadLayout();
        $this->renderLayout();
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('productdesigner/color');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('Color does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('color_data', $model);
 
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('productdesigner/color');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data); 
            
            if(!preg_match('/^#[a-f0-9]{6}$/i', $model->getValue())) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('Incorrect hex value'));
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }      
            
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('productdesigner')->__('Error saving color'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Color was successfully saved.'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('No data found to save'));
        $this->_redirect('*/*/');
    }
 
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('productdesigner/color');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Color has been deleted.'));
                               
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find color to delete.'));
        $this->_redirect('*/*/');
    }
   
}