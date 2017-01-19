<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Model_Observer {

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    const COOKIE_KEY_SOURCE = 'px_referal_uid';

    public function updateDealsLayout(Varien_Event_Observer $observer) {

        $layout = $observer->getLayout();

        //Adding dailydeals css
        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore())){

            $layout->getUpdate()->addUpdate(
                '<reference name="head">'
                . '<action method="addCss">'
                . '<stylesheet>dailydeals/css/dailydeals.css</stylesheet>'
                . '</action> '
                . '</reference>'
            );

        }

        //Add Deals link to top links menu acording to configuration
        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_show_in_header_links', Mage::app()->getStore())) {

            $layout->getUpdate()->addUpdate(
                    '<reference name="top.links">' .
                    '<action method="addLink" translate="label title" module="customer"><label>' . Mage::helper('peexl_dailydeals')->__('Deals') . '</label><url helper="peexl_dailydeals/getDealsUrl"/><title>' . Mage::helper('peexl_dailydeals')->__('Deals') . '</title><prepare/><urlParams/><position>50</position></action>' .
                    '</reference>');
        }

        // Add slider css and js if slider is enabled
        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::getStoreConfig('dailydeals/peexl_dailydeals_slider_block_configuration_group/peexl_dailydeals_slider_block_show', Mage::app()->getStore())) {
            $layout->getUpdate()->addUpdate(
                    '<reference name="head">' .
                    '<action method="addItem">' .
                    '<type>skin_js</type>' .
                    '<name>dailydeals/js/deals-slider.js</name>' .
                    '<params/>' .
                    '</action>' .
                    '<action method="addCss">' .
                    '<stylesheet>dailydeals/css/deals-slider.css</stylesheet>' .
                    '</action> ' .
                    '</reference>'
            );
        }

        //Show upcoming deal block

        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
            Mage::getStoreConfig('dailydeals/peexl_dailydeals_upcoming_deal_block_configuration_group/peexl_dailydeals_upcoming_deal_show', Mage::app()->getStore())) {



            $layout->getUpdate()->addUpdate(
                '<reference name="' . (Mage::getStoreConfig('dailydeals/peexl_dailydeals_upcoming_deal_block_configuration_group/peexl_dailydeals_upcoming_deal_sidebar', Mage::app()->getStore()) ? 'right' : 'left') . '">'
                . '<block type="peexl_dailydeals/catalog_product_deals_sidebar_upcomingdeal" name="dailydeals.product.deal.sidebar.upcoming" before="-"/>'
                . '</reference>'
            );

        }


        //Show most viewed deal block

        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::getStoreConfig('dailydeals/peexl_dailydeals_most_viewed_block_configuration_group/peexl_dailydeals_most_viewed_show', Mage::app()->getStore())) {
            $layout->getUpdate()->addUpdate(
                    '<reference name="' . (Mage::getStoreConfig('dailydeals/peexl_dailydeals_most_viewed_block_configuration_group/peexl_dailydeals_most_viewed_sidebar', Mage::app()->getStore()) ? 'right' : 'left') . '">'
                    . '<block type="peexl_dailydeals/catalog_product_deals_sidebar_mostviewdeal" name="dailydeals.product.deal.sidebar.mostviewdeal" before="-"/>'
                    . '</reference>'
            );
        }
        //Show top selling deal block

        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::getStoreConfig('dailydeals/peexl_dailydeals_top_selling_deal_block_configuration_group/peexl_dailydeals_top_selling_deal_show', Mage::app()->getStore())) {

            $layout->getUpdate()->addUpdate(
                    '<reference name="' . (Mage::getStoreConfig('dailydeals/peexl_dailydeals_top_selling_deal_block_configuration_group/peexl_dailydeals_top_selling_deal_sidebar', Mage::app()->getStore()) ? 'right' : 'left') . '">'
                    . '<block type="peexl_dailydeals/catalog_product_deals_sidebar_topselldeal" name="dailydeals.product.deal.sidebar.topselldeal" before="-"/>'
                    . '</reference>'
            );

        }

        //Show deal random block

        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::getStoreConfig('dailydeals/peexl_dailydeals_random_deal_block_configuration_group/peexl_dailydeals_random_deal_show', Mage::app()->getStore())) {


            $layout->getUpdate()->addUpdate(
                    '<reference name="' . (Mage::getStoreConfig('dailydeals/peexl_dailydeals_random_deal_block_configuration_group/peexl_dailydeals_random_deal_sidebar', Mage::app()->getStore()) ? 'right' : 'left') . '">'
                    . '<block type="peexl_dailydeals/catalog_product_deals_sidebar_randomdeal" name="dailydeals.product.deal.sidebar.random" before="-"/>'
                    . '</reference>'
            );

        }





        $layout->generateXml();
    }

    public function productPageViewed(Varien_Event_Observer $observer) {
        $_product = $observer->getEvent()->getProduct();
        if (Mage::helper('peexl_dailydeals')->isActiveDealProduct($_product)) {
            $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($_product);
            /**
             * Get the resource model
             */
            $resource = Mage::getSingleton('core/resource');

            /**
             * Retrieve the write connection
             */
            $writeConnection = $resource->getConnection('core_write');

            /**
             * Retrieve our table name
             */
            $table = $resource->getTableName('peexl_dailydeals/deals');

            $query = "UPDATE {$table} SET deal_views = deal_views+1 WHERE id = "
                    . (int) $dealData->getId();

            /**
             * Execute the query
             */
            $writeConnection->query($query);
        }
    }

    public function orderRegisterDeals(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        $items = $order->getAllItems();
        Mage::log(
                "{$order->getId()}", null, 'product-updates.log'
        );
        //    Mage::log($order->getId());
        foreach ($items as $item) {
            $itemId = $item->getProductId();
            $product = Mage::getModel('catalog/product')->load($itemId);
            if (Mage::helper('peexl_dailydeals')->isActiveDealProduct($product)) {
                $data["order_id"] = $order->getId();
                $data["product_id"] = $item->getProductId();
                $data["qty"] = $item->getQtyToInvoice();
                $data["price"] = $item->getPrice();
                $dealData = Mage::helper('peexl_dailydeals')->getProductDealData($product);
                $data["dailydeal_id"] = $dealData->getId();

                $sales = Mage::getModel('peexl_dailydeals/sales');
                $sales->setData($data);
                try {
                    $sales->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                    return;
                }
            }
        }
    }

    public function beforeAddToCart(Varien_Event_Observer $observer) {
        $productId = Mage::app()->getRequest()->getParam('product');
        $product = Mage::getModel('catalog/product')->load($productId);

        if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                Mage::helper('peexl_dailydeals')->isActiveDealProduct($product)) {

            $items_left = Mage::helper('peexl_dailydeals')->getItemRemainingQty($product);
            if (Mage::app()->getRequest()->getParam('qty') > $items_left) {
                Mage::app()->getResponse()->setRedirect($product->getProductUrl());
                Mage::getSingleton('checkout/session')->addError(Mage::helper('peexl_dailydeals')->__('Sorry,The maximum order qty available for the ' . $product->getName() . ' is ' . $items_left . '.'));
                $observer->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            }
        }
    }

    public function beforeUpdateCart(Varien_Event_Observer $observer) {
        $cart = $observer->cart;
        

        $data = $observer->info;
        foreach ($data as $itemId => $itemInfo) {
            $params=Mage::app()->getRequest()->getParam('cart');
            $item = $cart->getQuote()->getItemById($itemId);            
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            
            if (Mage::getStoreConfig('dailydeals/peexl_dailydeals_configuration_group/peexl_dailydeals_enable', Mage::app()->getStore()) &&
                    Mage::helper('peexl_dailydeals')->isActiveDealProduct($product)) {              
                $items_left = Mage::helper('peexl_dailydeals')->getItemRemainingQty($product);
                if ($params[$itemId]["qty"] > $items_left) {
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
                    Mage::getSingleton('checkout/session')->addError(Mage::helper('peexl_dailydeals')->__('Sorry,The maximum order qty available for the ' . $product->getName() . ' is ' . $items_left . '.'));
                    session_write_close(); //THIS LINE IS VERY IMPORTANT!    
                    Mage::app()->getResponse()->sendResponse();
                    exit;
                }
            }
        }
        
        
        //$observer->getControllerAction()->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
    }

}


