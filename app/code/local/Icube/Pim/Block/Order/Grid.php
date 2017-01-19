<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Order_Grid extends Mage_Core_Block_Template
{
    protected $_collection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

		$toolbar = $this->getLayout()->createBlock('pim/pager', 'pim.order.toolbar')
               ->setCollection($this->getOrderCollection());

        $this->setChild('toolbar', $toolbar);
                
        $this->getOrderCollection()->load();

        return $this;
    }
   
    protected function _applyRequestFilters($collection)
    {
        $r = Mage::app()->getRequest();
        $param = $r->getParam('filter_order_number');
        if (!is_null($param) && $param!=='') {
                    $collection->addAttributeToFilter('increment_id', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_name');
        if (!is_null($param) && $param!=='') {
            $collection->addFieldToFilter(
                array('customer_firstname','customer_lastname'),
                array(array('like'=>'%'.$param.'%'))
            );
        }
        $param = $r->getParam('filter_status');
        if (!is_null($param) && $param!=='') {
	        $collection->addAttributeToFilter('status', array('eq' => $param));
        }
        $param = $r->getParam('filter_order_date');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('created_at', array('like'=>'%'.$param.'%'));
        }

        return $this;
    }

    public function getOrderCollection()
    {
		if (!$this->_collection) 
		{
            $vendor = Mage::getSingleton('pim/session')->getVendor();
            if (!$vendor || !$vendor->getId()) {
                return array();
            }
		
	        $collection = Mage::getModel('sales/order')->getCollection()
                            ->addFieldToSelect('*')
                            ->addFieldToFilter('vendor_id', 
                                array('eq' => $vendor->getVendorId()))
                            ->setOrder('created_at', 'DESC');
	        
	        $this->_applyRequestFilters($collection);
	        $this->_collection = $collection;
		}
        return $this->_collection;
    }
    
    
    public function getOrderStatusOptions()
    {
	    $options = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
	    array_unshift($options,array("status"=>"","label"=> ""));
		return $options;
	}
    
}