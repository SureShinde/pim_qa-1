<?php

class Peexl_ProductDesigner_Adminhtml_FontController extends Mage_Adminhtml_Controller_Action
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
        $model = Mage::getModel('productdesigner/font');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productdesigner')->__('Font does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('font_data', $model);
 
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('productdesigner/font');
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }
            unset($data['file']);
            $model->setData($data);
            
            $fontFilename = null;

            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('file');

                    $uploader->setAllowedExtensions(array('ttf', 'eot'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $basePath  = Mage::helper('productdesigner')->getFontsBaseUrl();

                    $filename = $_FILES['file']['name'];
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
                    $fontFilename = $result['file'];
                }
                catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    if ($model && $model->getId()) {
                        $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    } else {
                        $this->_redirect('*/*/');
                    }
                }
            }

            if(isset($fontFilename))
                $model->setFile($fontFilename);
                
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('productdesigner')->__('Error saving font'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Font was successfully saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                
                $this->createFontsJson();
                
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
                $model = Mage::getModel('productdesigner/font');
                $model->setId($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productdesigner')->__('Font has been deleted.'));
                
                $this->createFontsJson();
                
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Unable to find font to delete.'));
        $this->_redirect('*/*/');
    }
    
    protected function createFontsJson() {
        $collection = Mage::getModel('productdesigner/font')->getCollection()->setOrder('position', 'ASC');

        if ($collection->getSize()) {
            $fontsArray['fonts'] = array();
            $css = '';
            foreach ($collection as $font) {
                $fontsArray['fonts'][] = array(
                        'name' => $font->getName(),
                        'fontFamily' => $font->getName()
                    );
                    
                if($font->getFile()) {    
                    $css .= '@font-face {
                                font-family: ' . $font->getName() . ';
                                src: url("' . Mage::helper('productdesigner')->getFontsBaseUrl() . $font->getFile() . '");
                                font-weight: normal;
                                font-style: normal;
                            }';
                }
            }
            file_put_contents(Mage::getBaseDir() . '/designer/config/fonts.json', json_encode($fontsArray));
            file_put_contents(Mage::getBaseDir() . '/designer/fonts/fonts.css', $css);
        }
    }
    
}