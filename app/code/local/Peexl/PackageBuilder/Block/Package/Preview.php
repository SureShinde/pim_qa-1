<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Block_Package_Preview extends Mage_Core_Block_Template {

    protected function _loadCache() {
        return false;
    }

    public function getProduct() {
        return Mage::registry('product');
    }

    public function getPackageSession() {
        return Mage::helper('packagebuilder')->getPackageSession();
    }

    public function getItems() {
        return $this->getPackageSession()->getItems();
    }

    public function getActiveItem() {
        return $this->getPackageSession()->getActiveItem();
    }
    public function getPreviewImage($item) {
      $previewImage = $item->getPreviewImage();
      if(substr($previewImage, 0, 3) == '[g]') {
        $image = $this->getProduct()->getMediaGalleryImages()->getItemByColumnValue('label', substr($previewImage, 3));
        if($image) return $image;
      } else {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 
               'package/' . $this->getProduct()->getId() . '/' . 
               $item->getPreviewImage();
      }
    }
}