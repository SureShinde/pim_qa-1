<?php

class Peexl_ProductDesigner_Adminhtml_GraphicsController extends Mage_Adminhtml_Controller_Action
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
        $model = Mage::getModel('productdesigner/graphics');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('Graphics does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('graphics_data', $model);
 
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('productdesigner/graphics');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            unset($data['image']);
            $model->setData($data);
            
            $imageFilename = null;

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('image');

                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png', 'svg'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $basePath = Mage::helper('productdesigner')->getGalleryImagesBasePath();

                    $filename = $_FILES['image']['name'];
                    $actualName = pathinfo($filename, PATHINFO_FILENAME);
                    $originalName = $actualName;
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);

                    $i = 1;
                    while (file_exists($basePath . $actualName . '.' . $extension)) {
                        $actualName = (string)$originalName . '_' . $i;
                        $filename = $actualName . '.' . $extension;
                        $i++;
                    }

                    // Upload the image
                    $result = $uploader->save($basePath, $filename);
                    $imageFilename = Mage::helper('productdesigner')->getGalleryImagesMediaPath() . $result['file'];
                }
                catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    if ($model && $model->getId()) {
                        $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    } else {
                        $this->_redirect('*/*/');
                    }
                }
            } else {
                if(isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $imageFilename = null;
                }
            }
            
            

            if(isset($imageFilename))
                $model->setImage($imageFilename);
                 
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('productdesigner')->__('Error saving graphics'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Graphics was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                               
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
                $model = Mage::getModel('productdesigner/graphics');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Graphics has been deleted.'));
                
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find graphics to delete.'));
        $this->_redirect('*/*/');
    }
    
}