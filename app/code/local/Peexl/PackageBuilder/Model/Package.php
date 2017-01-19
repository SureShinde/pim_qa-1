<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Package extends Mage_Catalog_Model_Product_Type_Abstract {

    const TYPE_CODE = 'package';
    
    const FIXED = 10;
    const DYNAMIC = 20;

    protected $_isComposite = true;
    protected $_old_version = false;

    public function save($product = null) {
        $item_ids = array();
        if ($items = $product->getItems()) {
            $item_options = $product->getItemOptions();

            foreach ($items as $key => $item) {
                $options = isset($item_options[$key]) ? $item_options[$key] : array();
                $product_ids = array();
                foreach ($options as $option) {
                    $product_ids[] = $option['product_id'];
                }
                //$options = implode(',', $product_ids);

                $item = new Varien_Object($item);
                $model = Mage::getModel('packagebuilder/package_item')
                        ->setCode($item->getCode())
                        ->setTitle($item->getTitle())
                        ->setDescription($item->getDescription())
                        ->setPrice($item->getPrice())
                        ->setSortOrder($item->getSortOrder())
                        ->setQty($item->getQty())
                        ->setIsOptional((int) $item->getIsOptional())
                        ->setProductId($product->getId())
                        ->setOptions($options);

                if ($item->getItemId())
                    $model->setItemId($item->getItemId());
                $model->save();
                $item_ids[] = $model->getItemId();
            }
        }
        $items = Mage::getModel('packagebuilder/package_item')->getCollection()->addFieldToFilter('product_id', $product->getId());
        if ($items) {
            foreach ($items as $item) {
                if (!in_array($item->getItemId(), $item_ids)) {
                    $item->setOptions(array())->save();
                    $item->delete();
                }
            }
        }
    }

    public function getItems() {
        $product = $this->getProduct();
        if ($product->getId()) {
            $collection = Mage::getModel('packagebuilder/package_item')->getCollection()->addFieldToFilter('product_id', $product->getId())->setOrder('sort_order', 'ASC');
            if (count($collection) > 0) {
                return $collection;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getOrderOptions($product = null) {
        $optionArr = parent::getOrderOptions($product);
        $optionArr['product_calculations'] = self::CALCULATE_CHILD;
        return $optionArr;
    }

    public function prepareForCart(Varien_Object $buyRequest, $product = null) {
        $this->_old_version = true;
        return $this->prepareForCartAdvanced($buyRequest, $product);
    }

    public function prepareForCartAdvanced(Varien_Object $buyRequest, $product = null, $processMode = null) {
        $package = $this->getProduct($product);

        if ($this->_old_version) {
            $candidates = parent::prepareForCart($buyRequest, $product);
        } else {
            $candidates = parent::prepareForCartAdvanced($buyRequest, $product);
        }
        
        if (is_string($candidates)) {
            Mage::getSingleton('core/session')->addError($candidates);
            return $candidates;
        }

        $candidates[0]->setCartQty(1);

        if (!isset($buyRequest['items']) && !is_array($buyRequest['items'])) {
            throw new Exception("A package must have selected components");
            return;
        }

        $items = Mage::getModel('packagebuilder/package_item')->getCollection()
                ->addFieldToFilter('product_id', $product->getId());
        if (count($items) == 0) {
            throw new Exception("Package product is not sellable.");
            return;
        }

        foreach ($items as $item) {
            if (!isset($buyRequest['items'][$item->getCode()])) {
                throw new Exception("Missing required component options");
                return;
            }
        }

        $price = 0.00;
        $totalWeight = 0.00;
        $packageVirtual = true;
        foreach ($buyRequest['items'] as $code => $options) {
            $product = Mage::getModel('catalog/product')->load($options['product_id']);
            if ($product->getId()) {
                $product->setParentProductId($package->getId());
                foreach ($items as $item) {
                    if ($item->getCode() == $code) {
                        $product->setCartQty($item->getQty())
                                ->addCustomOption('unique', rand(0, 999999999));
                    }
                }
                $totalWeight += $product->getWeight() * $product->getCartQty();
                $price += $product->getFinalPrice();
                if ($product->getTypeId() == Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE) {
                  $preparedLinks = array();
                  foreach ($product->getTypeInstance()->getLinks() as $link) {
                    $preparedLinks[] = $link->getId();
                  }
                  if ($preparedLinks) {
                    $product->addCustomOption('downloadable_link_ids', implode(',', $preparedLinks));
                  }
                } else {
                  $packageVirtual = false;
                }
                $isCandidate = false;
                foreach($candidates as $candidate) {
                    if($candidate->getId() == $product->getId()) {
                        $candidate = $product;
                        $isCandidate = true;
                        break;
                    }
                }
                if(!$isCandidate) {
                    $candidates[] = $product;
                }
            } else {
                throw new Exception("Invalid component option");
                return;
            }
        }
        
        // loop through all additional components
        if (0 && isset($buyRequest['additional'])) {
            foreach ($buyRequest['additional'] as $addon) {
                $product = Mage::getModel('catalog/product')->load($addon['product_id']);
                if ($product->getId()) {
                    $product->setParentProductId($package->getId())
                            ->setCartQty($addon['quantity'])
                            ->addCustomOption('unique', rand(0, 999999999));
                    $totalWeight += $product->getWeight() * $addon['quantity'];
                    $price += $product->getFinalPrice();
                    if ($product->getTypeId() == Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE) {
                      $preparedLinks = array();
                      foreach ($product->getTypeInstance()->getLinks() as $link) {
                        $preparedLinks[] = $link->getId();
                      }
                      if ($preparedLinks) {
                        $product->addCustomOption('downloadable_link_ids', implode(',', $preparedLinks));
                      }
                    } else {
                      $packageVirtual = false;
                    }
                    // add product to $components
                    $candidates[] = $product;
                } else {
                    throw new Exception("Invalid component option");
                    return;
                }
            }
        }
        $candidates[0]->addCustomOption('unique', rand(0, 999999999));
        $candidates[0]->addCustomOption('package_weight', $totalWeight);
        $candidates[0]->addCustomOption('package_price', $price);
        $candidates[0]->addCustomOption('package_virtual', $packageVirtual);

        return $candidates;
    }
    
    protected function _prepareProduct(Varien_Object $buyRequest, $product, $processMode)
    {
        $result = parent::_prepareProduct($buyRequest, $product, $processMode);

        if (is_string($result)) {
            return $result;
        }
        
        $package = $this->getProduct($product);
        
        $items = Mage::getModel('packagebuilder/package_item')->getCollection()
                ->addFieldToFilter('product_id', $product->getId());
        
        foreach ($buyRequest['items'] as $code => $options) {
            $product = Mage::getModel('catalog/product')->load($options['product_id']);
            if ($product->getId()) {
                $product->setParentProductId($package->getId());
                foreach ($items as $item) {
                    if ($item->getCode() == $code) {
                        $product->setCartQty($item->getQty())
                                ->addCustomOption('unique', rand(0, 999999999));
                    }
                }
                $totalWeight += $product->getWeight() * $product->getCartQty();
                $price += $product->getFinalPrice();
                if ($product->getTypeId() == Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE) {
                  $preparedLinks = array();
                  foreach ($product->getTypeInstance()->getLinks() as $link) {
                    $preparedLinks[] = $link->getId();
                  }
                  if ($preparedLinks) {
                    $product->addCustomOption('downloadable_link_ids', implode(',', $preparedLinks));
                  }
                } else {
                  $packageVirtual = false;
                }
                $result[] = $product;
            } else {
                throw new Exception("Invalid component option");
                return;
            }
        }
        
        $result[0]->addCustomOption('unique', rand(0, 999999999));
        $result[0]->addCustomOption('package_weight', $totalWeight);
        $result[0]->addCustomOption('package_price', $price);
        $result[0]->addCustomOption('package_virtual', $packageVirtual);
    
        return $result;
    }
    
    public function processBuyRequest($product, $buyRequest)
    {
        Mage::getSingleton('packagebuilder/package_session')->removePackageSession($product->getId());
        $session = Mage::getSingleton('packagebuilder/package_session')->getPackageSession($product);
        $requestItems = $buyRequest->getItems();
        $setActive = false;
        foreach($session->getItems() as $item) {
            if(isset($requestItems[$item->getCode()])) {
                $itemProduct = Mage::getModel('catalog/product')->load($requestItems[$item->getCode()]['product_id']);
                $item->setProduct($itemProduct);
                $item->setIsActive(false);
            } else {
                if(!$setActive) {
                    $item->setIsActive(true);
                    $setActive = true;
                }
            }
        } 
        if(!$setActive) {
            $session->setIsComplete();
        }
        return array();
    }
    
    /**
     * Check product's options configuration
     *
     * @param  Mage_Catalog_Model_Product $product
     * @param  Varien_Object $buyRequest
     * @return array
     */
    public function checkProductConfiguration($product, $buyRequest)
    {
        return array();
    }

    public function isSalable($product = null) {
        return true;
    }

    public function getWeight($product = null) {
        return $this->getProduct($product)->getCustomOption('package_weight')->getValue();
    }

    
  public function getPrice() {
    $product = $this->getProduct();
    $price = 0.00;
    if ($product->getData('price_type') == self::FIXED) {
      $items = Mage::getModel('packagebuilder/package_item')->getCollection()
              ->addFieldToFilter('product_id', $product->getId());
      foreach ($items as $item) {
        $price += $item->getPrice() * $item->getQty();
      }
    } else {
      if ($product->getCustomOption('package_price')) {
        $price = $product->getCustomOption('package_price')->getValue();
      } else {
        foreach (Mage::helper('packagebuilder')->getPackageSession()->getItems() as $item) {
          if (!$item->getIsComplete())
            continue;
          $price += $item->getProduct()->getFinalPrice();
        }
      }
    }
    return $price;
  }
 
 /**
   * Check is virtual product
   *
   * @param Mage_Catalog_Model_Product $product
   * @return bool
   */
 public function isVirtual($product = null) {
    $product = $this->getProduct($product);
    if ($product->getCustomOption('package_virtual')) {
      return $product->getCustomOption('package_virtual')->getValue();
    }
  }

}