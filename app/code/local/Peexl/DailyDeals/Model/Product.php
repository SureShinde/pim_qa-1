<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Model_Product extends Mage_Catalog_Model_Product {

    protected function _construct() {
        parent::_construct();
    }

    public function getSpecialPrice() {
        if(Mage::helper('peexl_dailydeals')->isActiveDealProduct($this) && Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) ){
            $dealData=Mage::helper('peexl_dailydeals')->getProductDealData($this);
            return $dealData->getDealPrice();
        }
        return parent::getSpecialPrice();
    }

}
