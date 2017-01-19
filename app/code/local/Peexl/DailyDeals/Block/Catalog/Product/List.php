<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Catalog_Product_List extends Mage_Catalog_Block_Product_List {

    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '', $displayPromo = true) {

        if (Mage::helper('peexl_dailydeals')->isActiveDealProduct($product) && Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore())) {
            $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($product);
            $product->setFinalPrice($dealData->getDealPrice());
        }
        return parent::getPriceHtml($product, $displayMinimalPrice, $idSuffix, $displayPromo);
    }
    
    public function getRemainingItems($product){
         $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($product);
         return $dealData->getDealQty()-Mage::helper('peexl_dailydeals')->getDealSalesQty($dealData->getId());
         
    }
    public function getDealStartDate($product){
        $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($product);
        $dealDate= date('d.m.Y',strtotime($dealData->getDateStart()));
        return $dealDate;
    }
}
