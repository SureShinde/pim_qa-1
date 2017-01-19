<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Package_Session extends Mage_Core_Model_Session_Abstract {

    public function __construct() {
        $this->init('package_product');
    }

    public function getPackageSession($product) {
        $currentPackage = null;
        if ($this->hasData('packages')) {
            $packages = $this->getData('packages');
            foreach ($packages as $package) {
                if ($package->getId() == $product->getId()) {
                    $currentPackage = $package;
                    break;
                }
            }
        }
        if (!$currentPackage) {
            $currentPackage = Mage::getModel('packagebuilder/package_session_product');
            $currentPackage->buildFromProduct($product);
            $packages = array($currentPackage);
            $this->setData('packages', $packages);
        }
        return $currentPackage;
    }
    
    public function resetSession() {
      $this->unsetData('packages');
    }

    public function getPackageSessionById($packageId) {
        if ($this->hasData('packages')) {
            $packages = $this->getData('packages');
            foreach ($packages as $package) {
                if ($package->getId() == $packageId) {
                    return $package;
                }
            }
        }
    }

    public function removePackageSession($packageId) {
        if ($this->hasData('packages')) {
            $packages = $this->getData('packages');
            for ($i = 0; $i < count($packages); $i++) {
                if ($packages[$i]->getId() == $packageId) {
                    unset($packages[$i]);
                }
            }
            $this->unsetData('packages');
            $this->setData('packages', $packages);
        }
        return $this;
    }

}