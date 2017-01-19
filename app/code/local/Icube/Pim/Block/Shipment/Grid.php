<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Shipment_Grid extends Mage_Core_Block_Template
{
    protected $_collection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

		$toolbar = $this->getLayout()->createBlock('pim/pager', 'pim.shipment.toolbar')
               ->setCollection($this->getShipmentCollection());

        $this->setChild('toolbar', $toolbar);
                
        $this->getShipmentCollection()->load();

        return $this;
    }
   
    protected function _applyRequestFilters($collection)
    {
        $r = Mage::app()->getRequest();
        $param = $r->getParam('filter_order_number');
        if (!is_null($param) && $param!=='') {
                    $collection->addAttributeToFilter('o.increment_id', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_shipment_number');
        if (!is_null($param) && $param!=='') {
	        $collection->addAttributeToFilter('main_table.increment_id', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_shipment_creation_date');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('main_table.created_at', array('like'=>$param.'%'));
        }
        $param = $r->getParam('filter_shipment_carrier');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('t.carrier_code', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_shipment_type');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('t.title', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_track_number');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('t.track_number', array('like'=>'%'.$param.'%'));
        }

        return $this;
    }

    public function getShipmentCollection()
    {
		if (!$this->_collection) 
		{	
			$store = Mage::app()->getStore();
            $vendor = Mage::getSingleton('pim/session')->getVendor();
            if (!$vendor || !$vendor->getId()) {
                return array();
            }
		
	        $collection = Mage::getModel('sales/order_shipment')->getCollection()
                            ->addFieldToSelect('*')
                            ->addFieldToFilter('main_table.vendor_id', 
                                array('eq' => $vendor->getVendorId()))
                            ->setOrder('main_table.created_at', 'DESC');
            $collection->getSelect()
                //->group('main_table.entity_id')
                ->join(array('o' => 'sales_flat_order'), 'o.entity_id = main_table.order_id ', array( 'order_inc_id'    => 'o.increment_id'))
                ->joinLeft(array('t' => 'sales_flat_shipment_track'), 'main_table.entity_id = t.parent_id AND t.order_id = o.entity_id', array(
                    'title' => 't.title',
                    'carrier' => 't.carrier_code',
                    'tracknum' => 't.track_number',
                ));
	        
	        $this->_applyRequestFilters($collection);
	        $this->_collection = $collection;
		}
        return $this->_collection;
    }
    
    
}