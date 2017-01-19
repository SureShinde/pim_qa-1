<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Package_Session_Product_Item extends Varien_Object {

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATE_INCOMPLETE = 'incomplete';
    const STATE_COMPLETE = 'complete';

    protected $_status;
    protected $_state;
    protected $_isUpgrade = false;
    protected $_product;
    protected $_preview_image;

    public function getId() {
        return $this->getItemId();
    }

    public function getIsActive() {
        return ($this->_status == self::STATUS_ACTIVE) ? true : false;
    }

    public function setIsActive($cond) {
        $this->_status = ($cond) ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
    }

    public function getIsComplete() {
        return ($this->_state == self::STATE_COMPLETE) ? true : false;
    }

    public function getIsUpgrade() {
        return $this->_isUpgrade;
    }

    public function setIsUpgrade($cond) {
        $this->_isUpgrade = $cond;
        return $this;
    }

    public function getStatus() {
        return $this->_status;
    }

    public function getState() {
        return $this->_state;
    }

    public function getProduct() {
        return $this->_product;
    }
    
    public function getPreviewImage() {
        return $this->_preview_image;
    }

    public function setProduct($product) {
        $this->_product = $product;
        $preview_image = Mage::helper('packagebuilder')->getOptionByProductId($this->getId(), $product->getId())->getPreviewImage(); 
        $this->_preview_image = $preview_image;
        $this->_state = self::STATE_COMPLETE;
        return $this;
    }

    public function getInstance() {
        return Mage::getModel('packagebuilder/package_item')->load($this->getId());
    }

    public function buildFromItem($item) {
        $this->_state = self::STATE_INCOMPLETE;
        $this->_status = self::STATUS_INACTIVE;
        $this->setData($item->getData());
        return $this;
    }

    public function reset() {
        $this->_state = self::STATE_INCOMPLETE;
        $this->_status = self::STATUS_INACTIVE;
        unset($this->_product);
    }

}