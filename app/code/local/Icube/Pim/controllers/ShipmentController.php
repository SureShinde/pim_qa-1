<?php
class Icube_Pim_ShipmentController extends Icube_Pim_Controller_PimAbstract
{		
	public function listAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Shipment List'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function viewAction()
	{
		$session = $this->_getSession();
		$id = (int) $this->getRequest()->getParam('id');
		
		$shipment = Mage::getModel('sales/order_shipment')->getCollection()
							->addFieldToSelect('*') 
							->addFieldToFilter('entity_id', $id)
							->addAttributeToFilter('vendor_id', 
                                array('eq' => $session->getVendorId()))
                            ->getFirstItem(); 

		if (!$shipment->getId())
		{
            $session->addError($this->__('Shipment is not found.'));
            $this->_redirect('pim/shipment/list');
            return;
        }
        $order = $shipment->getOrder();
        Mage::register('current_order', $order);
        Mage::register('current_shipment', $shipment);
        
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Shipment View'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}
	
	public function createAction()
	{
		$session = $this->_getSession();
		$id = (int) $this->getRequest()->getParam('id');
		
		$order = Mage::helper('pim')->getOrderById($id);

		if (!$order->getId())
		{
            $session->addError($this->__('Order is not found.'));
            $this->_redirect('pim/order/list');
            return;
        }
        
        Mage::register('current_order', $order);
        
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Order Ship'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}
	
	public function createsubmitAction()
	{
		$itemQty = array();
		$session = $this->_getSession();
		$post = $this->getRequest()->getPost();
		
		$order = Mage::helper('pim')->getOrderById($post['orderid']);

		if (!$order->getId())
		{
            $session->addError($this->__('Order is not found.'));
            $this->_redirect('pim/order/list');
            return;
        }
		
		if($order->canShip())
		{
			try
			{
				foreach($order->getAllItems() as $item)
				{
					$item_id = $item->getItemId();
					$qty = $item->getQtyOrdered() - $item->getQtyShipped() - $item->getQtyRefunded() - $item->getQtyCanceled();
					$itemQty[$item_id] = $qty;
				}

	            $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
	
	            $tracks = $this->getRequest()->getPost('tracking');
	            if ($tracks) {
	                foreach ($tracks as $data) {
	                    if (empty($data['number'])) {
	                        Mage::throwException($this->__('Tracking number cannot be empty.'));
	                    }
	                    $track = Mage::getModel('sales/order_shipment_track')
	                        ->addData($data);
	                    $shipment->addTrack($track);
	                }
	            }
	            
	            $shipment->register();
	            $shipment->getOrder()->setIsInProcess(true);
				$transactionSave = Mage::getModel('core/resource_transaction')
	            ->addObject($shipment)
	            ->addObject($shipment->getOrder())
	            ->save();

	            $order->setStatus('shipped');
		        $order->save();
	            
	            $this->_getSession()->addSuccess($this->__('The shipment has been created.'));
	            $this->_redirect('*/*/view', array('id' => $shipment->getId()));  
	            return;
	            
            }
	        catch (Exception $e)
	        {
	            Mage::logException($e);
	            $this->_getSession()->addError($this->__('Cannot save shipment.'));
	        }
        }
         
	    $this->_redirect('*/*/create', array('id' => $post['orderid']));    
        
	}

	public function editsubmitAction()
	{
		$post = $this->getRequest()->getPost();

		if($post['trackid'] != '') 
		{ 
    		$track = Mage::getModel('sales/order_shipment_track')->load($post['trackid'])      			
            	->setData('title', $post['tracking_title'])
            	->setData('number',$post['tracking_number'])
            	->setData('carrier_code', $post['tracking_carrier_code'])
            	->save();
        }
         
	    $this->_redirect('*/*/view', array('id' => $post['orderid']));    
        
	}
	
}