<?php

class Peexl_ProductDesigner_Adminhtml_Font_ColorController extends Mage_Adminhtml_Controller_Action
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
        $model = Mage::getModel('productdesigner/font_color');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('Font Color does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('font_color_data', $model);
 
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('productdesigner/font_color');
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
                    Mage::throwException(Mage::helper('productdesigner')->__('Error saving font color'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Font color was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                
                $this->createFontsColorsJson();
                
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
                $model = Mage::getModel('productdesigner/font_color');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Font color has been deleted.'));
                
                $this->createFontsColorsJson();
                
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find font color to delete.'));
        $this->_redirect('*/*/');
    }
    
    protected function createFontsColorsJson() {
        $colorsCollection = Mage::getModel('productdesigner/font_color')->getCollection()->setOrder('position', 'ASC');
        $colorsArray['colors'] = array();
        foreach ($colorsCollection as $color) {
            $colorsArray['colors'][] = array('name' => $color->getName(), 'value' => $color->getValue());
        }
        file_put_contents(Mage::getBaseDir() . '/designer/config/fontsColors.json', json_encode($colorsArray));
    }
    
}