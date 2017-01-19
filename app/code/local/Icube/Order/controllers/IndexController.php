<?php
class Icube_Order_IndexController extends Mage_Core_Controller_Front_Action
{
	public function importAction()
	{
		$xml = $this->getRequest()->getParam('xml');
		return Mage::helper('icube_order')->importOrder($xml);
	}
	
	public function testEmailOrderVendorAction()
	{
		// ex: order/index/testEmailOrderVendor/increment_id/11111/vendor_id/2222
		$params = $this->getRequest()->getParams();
		$order = Mage::getModel('sales/order')->load($params['increment_id'], 'increment_id');
		$vendor = Mage::getModel('pim/vendor')->load($params['vendor_id'], 'vendor_id');
		Mage::helper('icube_order')->sendEmailOrderVendor($order, $vendor);
	}

	
	public function cancelAction()
	{
		$data = (array) json_decode($this->getRequest()->getParam('data'));
		$data = (array) $data['Order_ID_Deallocate'];
		$data = (array) $data['TP_header'];
		$orderId = $data['BOOKID'];
		$orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('increment_id', array('like' => '%'.$orderId.'%'));
		$canceled = '';
		foreach ($orders as $order) {
			if ($order->canCancel()) {
			    try {
			        $order->cancel();

			        // remove status history set in _setState
			        $order->getStatusHistoryCollection(true);

			        $order->save();

			        $canceled .= $order->getIncrementId().',';
			    } catch (Exception $e) {
			        Mage::logException($e);
			    }
			}
		}

		Mage::log('Order canceled: '.$canceled,null,'so-cancel.log');
		echo 'Order canceled: '.$canceled;
	}
	
}