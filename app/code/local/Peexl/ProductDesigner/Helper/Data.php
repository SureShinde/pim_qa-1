<?php

/**
 * @category   Peexl
 * @package    Peexl_ProductDesigner
 * @copyright  Copyright (c) 2013 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Helper_Data extends Mage_Catalog_Helper_Data {
    
     public function getDesignerPath() {
        return Mage::getBaseDir() . '/designer/';
    }
    
    public function getFontsBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'productdesigner/fonts/';
    }
    
    public function getFontsBasePath() {
        return Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'fonts' . DS;
    }
       
    public function getGalleryImagesBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'productdesigner/gallery/images/';
    }
       
    public function getGalleryImagesBasePath() {
        return Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'gallery' . DS . 'images' . DS;
    }
    
    public function getGalleryImagesMediaPath() {
        return 'productdesigner/gallery/images/';
    }
    
    public function getPatternsBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'productdesigner/gallery/patterns/';
    }
       
    public function getPatternsBasePath() {
        return Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'gallery' . DS . 'patterns' . DS;
    }
    
    public function getPatternsMediaPath() {
        return 'productdesigner/gallery/patterns/';
    }
       
    public function getImagesBasePath() {
        return Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'images' . DS;
    }
       
    public function getImagesBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'productdesigner/images/';
    }
    
    public function getResultsBasePath() {
        return Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'results' . DS;
    }
    
    public function getResultsBaseUrl() {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'productdesigner/results/';
    }
}

