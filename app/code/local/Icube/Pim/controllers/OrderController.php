<?php
class Icube_Pim_OrderController extends Icube_Pim_Controller_PimAbstract
{	
	public function listAction()
	{
		$this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Order List'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}

	public function viewAction()
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
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vendor Order View'));
        $this->_initLayoutMessages('pim/session');
        $this->renderLayout();
	}
	
	public function viewsubmitAction()
	{
		$session = $this->_getSession();
		$post = $this->getRequest()->getPost();
		
		$order = Mage::helper('pim')->getOrderById($post['orderid']);
		if (!$order->getId())
		{
            $session->addError($this->__('Order is not found.'));
            $this->_redirect('pim/order/list');
            return;
        }
		
		switch($post['action'])
        {
            case 'order_confirm' :
            	$order->setStatus('confirmed');
		        $order->save();
		        $session->addSuccess('The order has been updated.');

	            $arrayid = explode("-", $order->getIncrementId(), 2);
	            $vendorid = $arrayid[0];
	            $mroid = $arrayid[1];
	            $data = array('order_inc_id' => $mroid, 'vendor_id' => $vendorid, 'status' => 'confirmed');
	            $this->sendToMro($data);
            break;
            
            case 'cannot_fulfill' :
            	if($order->getStatus() != 'cannot_fulfill')
            	{
	            	$order->setStatus('cannot_fulfill');
					$order->save();
					$session->addSuccess('The order has been updated.');
					// Sending Email to klikmro

					$arrayid = explode("-", $order->getIncrementId(), 2);
		            $vendorid = $arrayid[0];
		            $mroid = $arrayid[1];
		            $data = array('order_inc_id' => $mroid, 'vendor_id' => $vendorid, 'status' => 'cannot_fulfill');
		            $this->sendToMro($data);
            	} 
            break;
            
            case 'order_ship' :
            	if($order->getStatus() != 'confirmed' || $order->getStatus() == 'cannot_fulfill')
				{
					$session->addError($this->__('Order must be confirmed.'));
				}
				else
				{
					$this->_redirectUrl(Mage::getUrl('pim/shipment/create/', array('id' =>  $post['orderid'])));
					$arrayid = explode("-", $order->getIncrementId(), 2);
					$vendorid = $arrayid[0];
		            $mroid = $arrayid[1];
		            $data = array('order_inc_id' => $mroid, 'vendor_id' => $vendorid, 'status' => 'shipped');
		            $this->sendToMro($data);
					return;
				}
            break;
            
            default :
                    Mage::register('current_order', $order);
					$this->_redirectUrl(Mage::getUrl('pim/order/view/', array('id' =>  $post['orderid'])));
            break;
        }
        
        Mage::register('current_order', $order);
		$this->_redirectUrl(Mage::getUrl('pim/order/view/', array('id' =>  $post['orderid'])));

	}	

	public function sendToMro($data){
        $url = Mage::getStoreConfig('icube_order/mro_order/url',Mage::app()->getStore());

        ////code for send data via soap
        // $username = Mage::getStoreConfig('icube_order/mro_order/api_user',Mage::app()->getStore());
        // $password = Mage::getStoreConfig('icube_order/mro_order/api_key',Mage::app()->getStore());

        // // $url = 'http://local.pim.com/api/soap/?wsdl=1';
        // // $username = 'kancalawas';
        // // $password = 'password123';
        // $soap = new SoapClient($url, array("trace" => 1,
        //   "exceptions" => 1));
        // $session_id = $soap->login($username,$password);
        // $result = $soap->call($session_id, 'order_api.updatefromvendor',array('parameters' => $data));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); //timeout after 300 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.json_encode($data));
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        $result=curl_exec($ch);
        curl_close($ch);
        Mage::log($result,null,'mro-update.log');
	}
}