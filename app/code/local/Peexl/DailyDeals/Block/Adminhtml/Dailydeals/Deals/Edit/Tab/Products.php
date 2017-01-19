<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
      //  $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
//        $this->setDefaultPage(2);
        $this->setVarNameFilter('product_filter');
    }

    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection() {
        $collection= Mage::helper('peexl_dailydeals')->getDealsProductsGridCollection();

        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites', 'catalog/product_website', 'website_id', 'product_id=entity_id', null, 'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {

        $this->addColumn('product', array(
            'header_css_class' => 'a-center',
            'type' => 'radio',            
            'name' => 'product',
            'html_name' => 'grid_product_id',      
            'values' => $this->_getSelectedProduct(),
            'align' => 'center',
            'index' => 'entity_id',
            'renderer' => 'Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tab_Grid_Renderer_Radio'
           
        ));
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id',
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
        ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name', array(
                'header' => Mage::helper('catalog')->__('Name in %s', $store->getName()),
                'index' => 'custom_name',
            ));
        }

        $this->addColumn('type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

        $this->addColumn('set_name', array(
            'header' => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width' => '100px',
            'index' => 'attribute_set_id',
            'type' => 'options',
            'options' => $sets,
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80px',
            'index' => 'sku',
        ));

        $store = $this->_getStore();
        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'price',
        ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty', array(
                'header' => Mage::helper('catalog')->__('Qty'),
                'width' => '100px',
                'type' => 'number',
                'index' => 'qty',
            ));
        }

        $this->addColumn('visibility', array(
            'header' => Mage::helper('catalog')->__('Visibility'),
            'width' => '70px',
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('catalog')->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));


        $this->addColumn('action', array(
            'header' => Mage::helper('catalog')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('catalog')->__('Edit'),
                    'url' => array(
                        'base' => '*/*/edit',
                        'params' => array('store' => $this->getRequest()->getParam('store'))
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
        ));



        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/prodgrid', array('_current' => true));
    }

    public function getRowUrl($row) {        
        return $this->getUrl('*/*/edit', array(
                    'store' => $this->getRequest()->getParam('store'),
                    'id' => $row->getId())
        );
    }

    /**
     * Retrieve selected product
     *
     * @return array
     */
    
    public function prepareCollection(){
        $this->_prepareCollection();
    }
    
    public function preparePage(){
        $this->_preparePage();
    }
    
    public function prepareColumns(){
        $this->_prepareColumns();
    }
    public function _getSelectedProduct() {
        
        if (Mage::registry('px_dailydeals_deal_data')) {
            return array(Mage::registry('px_dailydeals_deal_data')->getProductId());
            
        }
        return array();
    }
    
    public function findProductPage($productID=0,$page=1){
        $collection = $this->getCollection();        
        $ids=$this->getAllIds();              
        return $this;
    }

    
    public function getAllIds(){
        $ids=array();
        $collection = $this->getCollection();
        $idx=0;
        
        foreach ($collection->getItems() as $item){
            
            $ids[$idx]=(int)$item->getId();
            $idx++;
        }
        
        return $ids;
    }

    public function getDefaultSort(){
        return $this->_defaultSort;
    }
    
    public function getDefaultDir(){
        return $this->_defaultDir;
    }
    
    public function getDeafaultLimit(){
        return $this->_defaultLimit;
    }
    
}
