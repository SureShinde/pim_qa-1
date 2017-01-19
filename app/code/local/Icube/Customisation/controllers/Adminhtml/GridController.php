<?php
 
class Icube_Customisation_Adminhtml_GridController extends Mage_Adminhtml_Controller_Action
{
    public function setAsApprovedAction()
    {
        // Update status here

        $ids = $this->getRequest()->getParam('product');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('catalog')->__('Please select product(s).'));
        } else {
            if (!empty($ids)) {
                try {
                    $approval = (int) $this->attributeValueExists('approval_status', 'approved');
                    $products = Mage::getModel('catalog/product')->getCollection()
                                ->addAttributeToFilter('entity_id', array('in' => $ids));
                    foreach($products as $product)
                    {
                        $product->setApprovalStatus($approval);
                        $product->getResource()->saveAttribute($product, 'approval_status');
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been updated.', count($ids))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
         
        $this->_redirect('adminhtml/catalog_product/');
    }

    public function setAsNotApprovedAction()
    {
        // Update status here

        $ids = $this->getRequest()->getParam('product');
        $reason = $this->getRequest()->getParam('reason');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('catalog')->__('Please select product(s).'));
        } else {
            if (!empty($ids)) {
                try {
                    $approval = (int) $this->attributeValueExists('approval_status', 'not approved');
                    $products = Mage::getModel('catalog/product')->getCollection()
                                ->addAttributeToFilter('entity_id', array('in' => $ids));
                    foreach($products as $product)
                    {
                        $product->setApprovalStatus($approval);
                        $product->getResource()->saveAttribute($product, 'approval_status');
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been updated.', count($ids))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
         
        $this->_redirect('adminhtml/catalog_product/');
    }

    public function attributeValueExists($arg_attribute, $arg_value)
    {
        return Mage::getResourceModel('catalog/product')
                            ->getAttribute($arg_attribute)
                            ->getSource()
                            ->getOptionId($arg_value);
    }
}