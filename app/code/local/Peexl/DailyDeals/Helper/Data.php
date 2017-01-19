<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Helper_Data extends Mage_Catalog_Helper_Data {

    public function getDealsUrl() {
        return Mage::getUrl('dailydeals');
    }

    public function getActiveDealsProducts() {
        $products = array();
        $date = date('Y-m-d H:i:s');
        $deals = Mage::getModel("peexl_dailydeals/deals")
                ->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("deal_status", 1)
                ->addFieldToFilter("date_start", array(array('lteq' => $date, 'date' => true)))
                ->addFieldToFilter("date_end", array(array('gteq' => $date, 'date' => true)))

        ;
        foreach ($deals as $deal) {
            $products[] = $deal->getProductId();
        }

        return $products;
    }
    public function getMostViewedProducts() {
        $products = array();
        $date = date('Y-m-d H:i:s');
        $deals = Mage::getModel("peexl_dailydeals/deals")
                ->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("deal_status", 1)
                ->addFieldToFilter("date_start", array(array('lteq' => $date, 'date' => true)))
                ->addFieldToFilter("date_end", array(array('gteq' => $date, 'date' => true)))
                ->setOrder('deal_views','DESC')
        ;
        foreach ($deals as $deal) {
            $products[] = $deal->getProductId();
            break;
        }

        return $products;
    }

    public function getUpcomingDealsProducts() {
        $products = array();
        $date = date('Y-m-d H:i:s');
        $deals = Mage::getModel("peexl_dailydeals/deals")
            ->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter("deal_status", 1)
            ->addFieldToFilter("date_start", array(array('gteq' => $date, 'date' => true)))
            //->addFieldToFilter("date_end", array(array('gteq' => $date, 'date' => true)))
            ->setOrder('date_start','ASC')
        ;
        foreach ($deals as $deal) {
            $products[] = $deal->getProductId();
            break;
        }

        return $products;
    }

    public function getMostViewedDeal(){
        $products = array();
        $date = date('Y-m-d H:i:s');
        $deals = Mage::getModel("peexl_dailydeals/deals")
                ->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("deal_status", 1)
                ->addFieldToFilter("date_start", array(array('lteq' => $date, 'date' => true)))
                ->addFieldToFilter("date_end", array(array('gteq' => $date, 'date' => true)))
                ->setOrder('deal_views','desc')
                ->getFirstItem()

        ;

            $products[] = $deals->getProductId();


        return $products;
    }

    public function getProductDealData($_product) {
        $deal = Mage::getModel("peexl_dailydeals/deals")
                ->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("product_id", $_product->getEntityId())
                ->addFieldToFilter("deal_status", 1)
                //->addFieldToFilter("date_start", array(array('lteq' => $date, 'date' => true)))
                //->addFieldToFilter("date_end", array(array('gteq' => $date, 'date' => true)))
                ->getFirstItem()
        ;
        return $deal;
    }

    public function getDealData($dealId) {
        $date = date('Y-m-d H:i:s');
        $deal = Mage::getModel("peexl_dailydeals/deals")
                ->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter("id", $dealId)
                ->getFirstItem()
        ;
        return $deal;
    }
    public function isActiveDealProduct($_product) {
        return in_array($_product->getEntityId(), $this->getActiveDealsProducts());
    }

    public function getProductTimer($_product) {
        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) && Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_show_countdown_timer', Mage::app()->getStore()) && $this->isActiveDealProduct($_product)) {
            $dealData = $this->getProductDealData($_product);
            return '<div id="cnt-' . $_product->getEntityId() . '"></div>' .
                    '<script type="text/javascript">new DaysHMSCounter(\'cnt-' . $_product->getEntityId() . '\',{initDate:' . strtotime($dealData->getDateEnd()) . ',txtColor:\''.Mage::getStoreConfig('dailydeals/peexl_dailydeals_color_configuration_group/peexl_dailydeals_configuration_countdown_timer_notes_color', Mage::app()->getStore()).'\',bkgColor:\''.Mage::getStoreConfig('dailydeals/peexl_dailydeals_color_configuration_group/peexl_dailydeals_configuration_countdown_timer_bkg_color', Mage::app()->getStore()).'\',nrColor:\''.Mage::getStoreConfig('dailydeals/peexl_dailydeals_color_configuration_group/peexl_dailydeals_configuration_countdown_timer_text_color', Mage::app()->getStore()).'\'});</script>'
            ;
        }
        return false;
    }

    public function dealSaveImage($_product){
        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) && $this->isActiveDealProduct($_product)) {
            $dealData=$this->getProductDealData($_product);
            $pct=round(100 - ($dealData->getDealPrice()*100/$_product->getPrice()),1);
            return '<div class="peexl-dailydeals-save">'.$this->__('Save').'<br>'.$pct.'%</div>';
        }
    }


    /*
     * Creating deals product grid for form
     * 
     * @return Varien_Data_Collection
     */
    public function getDealsProductsGridCollection(){
        $store = Mage::app()->getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('entity_id')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('attribute_set_id')
                ->addAttributeToSelect('type_id')
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ;


        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                    'name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore
            );
            $collection->joinAttribute(
                    'custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId()
            );
            $collection->joinAttribute(
                    'status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId()
            );
            $collection->joinAttribute(
                    'visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId()
            );
            $collection->joinAttribute(
                    'price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId()
            );
        } else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        return $collection;
    }

    /*
     * Geting the default product page in grid
     * 
     * @param int $productID
     * @return int
     */
    public function getDealProductGridPage($productID){
        $page=1;
        $block= new Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit_Tab_Products();
        $collection=$this->getDealsProductsGridCollection();

        $collection->setOrder($block->getDefaultSort(),$block->getDefaultDir());
        $ids=$collection->getColumnValues('entity_id');
        $item_position=(int) array_search($productID, $ids) +1;

        $limit=$block->getDeafaultLimit();

        if($item_position>$limit){
            $page=(int) ($item_position/$limit);
            if($item_position % $limit >0){
                $page++;
            }
        }

        return $page;


    }

   public function getDealSalesQty($dealId){
        $deal = Mage::getModel("peexl_dailydeals/sales")
                ->getCollection()
                //->addFieldToSelect('*')
                ->addFieldToFilter("dailydeal_id", $dealId)
                ->addExpressionFieldToSelect('total_qty','IFNULL(SUM({{qty}}),0)','qty')
                ->getFirstItem()
        ;
        return $deal->getTotalQty();
   }

   public function getTopSellDeal(){
        $products = array();
         $deal = Mage::getModel("peexl_dailydeals/sales")
                ->getCollection()
                ->addFieldToSelect('dailydeal_id')
                ->addExpressionFieldToSelect('total_qty','IFNULL(SUM({{qty}}),0)','qty')
                ->setOrder('total_qty', 'DESC')
        ;
         $deal->getSelect()->group('dailydeal_id');
        $item = $deal->getFirstItem();
        $dealData=$this->getDealData($item->getDailydealId());
        $products[]=$dealData->getProductId();
        return $products;
   }
   public function getItemRemainingQty($product){
        $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($product);
         $qty= $dealData->getDealQty()-$this->getDealSalesQty($dealData->getId());
         return $qty;
   }



}
