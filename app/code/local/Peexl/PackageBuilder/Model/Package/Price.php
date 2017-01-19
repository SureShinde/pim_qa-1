<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Package_Price extends Mage_Catalog_Model_Product_Type_Price {

  public function getFinalPrice($qty = null, $product) {
      $finalPrice = $product->getTypeInstance()->getPrice();
      $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);
    return $finalPrice;
  }

}
