<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Product_Grid extends Mage_Core_Block_Template
{
    protected $_collection;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

		$toolbar = $this->getLayout()->createBlock('pim/pager', 'pim.product.toolbar')
                ->setCollection($this->getProductCollection());

        $this->setChild('toolbar', $toolbar);
                
        $this->getProductCollection()->load();

        return $this;
    }
   
    protected function _applyRequestFilters($collection)
    {
        $r = Mage::app()->getRequest();
        $param = $r->getParam('filter_vendor_sku');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('vendor_sku', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_name');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('name', array('like'=>'%'.$param.'%'));
        }
        $param = $r->getParam('filter_approval_status');
        if (!is_null($param) && $param!=='') {
	        $collection->addAttributeToFilter('approval_status', array('eq' => $param));
        }
        $param = $r->getParam('filter_qty_from');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('qty', array('gteq'=>$param));
        }
        $param = $r->getParam('filter_qty_to');
        if (!is_null($param) && $param!=='') {
            $collection->addAttributeToFilter('qty', array('lteq'=>$param));
        }
        return $this;
    }

    public function getProductCollection()
    {
		if (!$this->_collection) 
		{	
			$store = Mage::app()->getStore();
            $vendor = Mage::getSingleton('pim/session')->getVendor();
            if (!$vendor || !$vendor->getId()) {
                return array();
            }
		
	        $collection = Mage::getModel('catalog/product')->getCollection()
	            ->addAttributeToSelect('sku')
	            ->addAttributeToSelect('vendor_sku')
	            ->addAttributeToSelect('name')
	            ->addAttributeToSelect('approval_status')
	            ->addAttributeToSelect('attribute_set_id')
	            ->addAttributeToFilter('vendor_id', 
	                array('eq' => Mage::helper('pim')->getVendorAttributeOptionId($vendor->getVendorId()))
	                );
	
	        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
	            $collection->joinField('qty',
	                'cataloginventory/stock_item',
	                'qty',
	                'product_id=entity_id',
	                '{{table}}.stock_id=1',
	                'left');
	        }
	        if ($store->getId()) {
	            $collection->joinAttribute(
	                'custom_name',
	                'catalog_product/name',
	                'entity_id',
	                null,
	                'inner',
	                $store->getId()
	            );
	            $collection->joinAttribute(
	                'price',
	                'catalog_product/price',
	                'entity_id',
	                null,
	                'left',
	                $store->getId()
	            );
	        }
	        else {
	            $collection->addAttributeToSelect('price');
	        }
	        
	        $this->_applyRequestFilters($collection);
	        $this->_collection = $collection;
		}
        return $this->_collection;
    }
    
    public function getProductSetName($id)
    {
    	$set = Mage::getModel("eav/entity_attribute_set")
    			->load($id)
    			->getAttributeSetName();

        return $set;
    }
    
    public function getApprovalStatusOptions()
    {
	    $options = Mage::getResourceModel('catalog/product')
	                        ->getAttribute('approval_status')
	                        ->getSource()
	                        ->getAllOptions();
		return $options;
	}
	
	public function getEditUrl($id)
    {
        return $this->getUrl('pim/product/edit', array('id' => $id));
    }
    
    public function getDeleteUrl($id)
    {
        return $this->getUrl('pim/product/delete', array('id' => $id));
    }
    
}