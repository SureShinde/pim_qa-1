<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_PackageBuilder_Model_Package_Session_Product {

    const STATE_COMPLETE = 'complete';
    const STATE_INCOMPLETE = 'incomplete';

    protected $_state;
    protected $_items = array();
    protected $_id;
    protected $_url;

    public function getId() {
        return $this->_id;
    }

    public function getUrl() {
        return $this->_url;
    }

    public function getIsComplete() {
        return ($this->_state == self::STATE_COMPLETE) ? true : false;
    }
    
    public function setIsComplete() {
        $this->_state = self::STATE_COMPLETE;
    }

    public function getItems() {
        return $this->_items;
    }

    public function getItemById($itemId) {
        foreach ($this->_items as $item) {
            if ($item->getId() == $itemId) {
                return $item;
            }
        }
    }

    public function getActiveItem() {
        foreach ($this->getItems() as $item) {
            if ($item->getIsActive()) {
                return $item;
            }
        }
    }

    public function setActiveItem($itemId) {
        foreach ($this->getItems() as $item) {
            if ($item->getId() == $itemId) {
                $item->setIsActive(true);
            } else {
                $item->setIsActive(false);
            }
        }
        $this->_state = self::STATE_INCOMPLETE;
    }

    public function buildFromProduct($product) {
        $this->_id = $product->getId();
        $this->_url = Mage::getBaseUrl('web') . $product->getUrlPath();
        $items = $product->getTypeInstance()->getItems();
        if (count($items) > 0) {
            foreach ($items as $item) {
                $itemSession = Mage::getModel('packagebuilder/package_session_product_item')->buildFromItem($item);

                if (!$this->_items) {
                    $this->_items = array();
                    $itemSession->setIsActive(true);
                }
                $this->_items[] = $itemSession;
            }
        }
        return $this;
    }

    public function incrementActiveItem() {
        $this->getActiveItem()->setIsActive(false);
        foreach ($this->getItems() as $item) {
            if (!$item->getIsComplete()) {
                $item->setIsActive(true);
                return $this;
            }
        }
        $this->_state = self::STATE_COMPLETE;
        return $this;
    }

    public function reset() {
        $this->_state = self::STATE_INCOMPLETE;
        foreach ($this->_items as $item) {
            $item->reset();
        }
        // set the first component as the active
        $this->_items[0]->setIsActive(true);
        return $this;
    }

}