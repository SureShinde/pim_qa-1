<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0.3
 */

class Peexl_ImportExportOrders_Adminhtml_ImportexportController extends Mage_Adminhtml_Controller_Action
{



    public function importAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Import/Export'))
            ->_title($this->__('Import Orders'));

        $this->loadLayout()
            ->_setActiveMenu('system/convert_importexportexport')
            ->renderLayout();
    }

    public function exportAction()
    {
        $this->_title($this->__('System'))
            ->_title($this->__('Import/Export'))
            ->_title($this->__('Export Orders'));

        $this->loadLayout()
             ->_setActiveMenu('system/convert_importexportexport');
       $this->renderLayout();
    }

    /**
     * import action from import/export orders
     *
     */
    public function importPostAction()
    {

        if ($this->getRequest()->isPost() && !empty($_FILES['import_orders_file']['tmp_name'])) {
            try {

                $orders_type = $this->getRequest()->getPost( 'index_import');
                $storeId = $this->getRequest()->getPost('store_view');
                $createInvoices=$this->getRequest()->getPost('invoice_create');
                $createShipping=$this->getRequest()->getPost('shipment_create');

                $model=Mage::getModel('importexportorders/importexport');
                $model->setData($this->getRequest()->getParams());

                $model->_importOrders();

//                $orders = Mage::helper('importexportorders/data');
//                $orders->_importOrders($storeId,$orders_type,$createInvoices,$createShipping);


                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tax')->__('Orders were imported.'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tax')->__('Invalid file upload attempt'));
        }
        $this->_redirect('*/*/import');
    }

    
    /**
     * export action from import/export orders
     *
     */
    public function exportPostAction()
    {

    /* START - Export action */
        $model=Mage::getModel('importexportorders/importexport');
        $model->setData($this->getRequest()->getParams());

        $response=$model->_exportOrders();


        echo json_encode($response);

        return false;
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('importexportorders/adminhtml_sales_order_grid')->toHtml()
        );
    }

    public function downloadAction(){

        $dir = Mage::getBaseDir('export');
        $file=$this->getRequest()->getParam('f');
        $this->_prepareDownloadResponse("ExportedOrders-".time().".xls", array("type"=>'filename',"value"=>$dir.'/'.$file,"rm"=>0));

    }
}