<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */

class Peexl_DailyDeals_Block_Catalog_Product_Deals_Slider extends Peexl_DailyDeals_Block_Catalog_Product_Deals_List{
    function __construct() {
        parent::__construct();                       
        $this->setTemplate('peexl/dailydeals/product/deals/slider.phtml');
    }
}

