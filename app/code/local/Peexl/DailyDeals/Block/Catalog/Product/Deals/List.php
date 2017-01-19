<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Catalog_Product_Deals_List extends Peexl_DailyDeals_Block_Catalog_Product_List {

    function __construct() {

        parent::__construct();

        $this->setTemplate('peexl/dailydeals/product/deals/list.phtml');
    }

    protected function _getProductCollection() {     
        if (is_null($this->_productCollection)) {
            $collection = Mage::getModel('catalog/product')
                    ->getCollection('*')
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id', array('in', (count(Mage::helper('peexl_dailydeals')->getActiveDealsProducts())>0?Mage::helper('peexl_dailydeals')->getActiveDealsProducts():array(0))));
            Mage::getModel('catalog/layer')->prepareProductCollection($collection);
            $this->_productCollection = $collection;
            
        }
        return parent::_getProductCollection();
    }

    public function getCountdownTimerScript($_product) {
        return
                Mage::helper('peexl_dailydeals')->getProductTimer($_product);
    }

    public function dealSaveImage($_product) {
        return
                Mage::helper('peexl_dailydeals')->dealSaveImage($_product);
    }

}
