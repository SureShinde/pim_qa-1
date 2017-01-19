<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_Model_Observer {

    static protected $_singletonFlag = false;

    public function saveProductDesignerOptions(Varien_Event_Observer $observer) {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;

            $product = $observer->getEvent()->getProduct();
            $productId = $product->getId();
            $designer = $product->getDesigner();
            $deleteValues = array();
            //print_r($designer);
            //print_r($_FILES);
            //die;
            if (count($designer)) {
                foreach ($designer as $optionId => $option) {
                    if(strstr($optionId, '_') !== false) continue;
                    foreach ($option['values'] as $valueId => $value) {
                        if ($value['is_delete']) {
                            $deleteValues[] = $valueId;
                            if($this->contains($value['value'], array('.swf', '.jpg', '.png', '.jpeg', '.gif', '.svg'))) {
                                $_value = Mage::getModel('productdesigner/option_value')->load($valueId);
                                if($_value->getValue()) {
                                    $valueInfo = @unserialize($_value->getValue());
                                    if(is_array($valueInfo)) {
                                        $path = $valueInfo['path'];
                                    } else {
                                        $path = $_value->getValue();
                                    }
                                    if (file_exists(Mage::helper('productdesigner')->getImagesBasePath() . $path)) {
                                        unlink(Mage::helper('productdesigner')->getImagesBasePath() . $path);
                                    }
                                }
                            }
                        } else {
                            $_value = Mage::getModel('productdesigner/option_value')->load($valueId);

                            $isNew = true;
                            if ($_value->getId())
                                $isNew = false;
                            
                            if(isset($designer[$optionId . '_' . $valueId])) {
                                $colorValues = array();
                                foreach($designer[$optionId . '_' . $valueId]['values'] as $colorValue) {
                                    if(!$colorValue['is_delete']) {
                                        $colorValues[] = $colorValue;
                                    }
                                }
                                $value['value'] = serialize(array('value' => $value['value'], 'colors' => $colorValues));                               
                            }
                            
                            if (isset($_FILES['designer_option_'.$optionId]) && 
                                isset($_FILES['designer_option_'.$optionId]['name'][$valueId]) &&   
                                    $_FILES['designer_option_'.$optionId]['name'][$valueId] != '') {
                                try {
                                    $uploader = new Varien_File_Uploader('designer_option_'.$optionId.'['.$valueId.']');
                                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'swf', 'svg'));
                                    $uploader->setAllowRenameFiles(false);
                                    $uploader->setFilesDispersion(false);
                                    $path = Mage::helper('productdesigner')->getImagesBasePath();
                                    $id = $productId . '_' . $optionId .'_'. $valueId;
                                    $actualName = pathinfo($_FILES['designer_option_'.$optionId]['name'][$valueId]['name'], PATHINFO_FILENAME);
                                    $filename = $actualName . '_' . $id . '.' . $uploader->getFileExtension();
                                    $val = $filename;
                                    
                                    if(!$isNew) {
                                        $valueInfo = unserialize($_value->getValue());
                                        if (file_exists(Mage::helper('productdesigner')->getImagesBasePath() . $valueInfo['value'])) {
                                            unlink(Mage::helper('productdesigner')->getImagesBasePath() . $valueInfo['value']);
                                        }
                                    }
                                    
                                    $uploader->save($path, $filename);
                                    
                                    
                                    if($value['is_color']) {
                                        $value['color_image'] = $val;
                                        
                                    } else {
                                        $value['value'] = $val;
                                    }
                                    
                                } catch (Exception $e) {
                                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                                }
                            }
                            /*
                            if($this->contains($value['value'], array('.swf', '.jpg', '.png', '.jpeg', '.gif', '.svg')) || isset($value['color_image'])) {
                                $value['value'] = serialize(array('path' => $value['value'], 'offset_x' => $value['offset_x'], 'offset_y' => $value['offset_y'],
                                                                  'size_x' => $value['size_x'],'size_y' => $value['size_y'], 'color_image' => isset($value['color_image'])?$value['color_image']:''));
                            }
                            */
                            unset($value['is_delete']);
                            $_value->setProductId($productId)
                                    ->setOptionId($optionId)
                                    ->setValue(serialize($value));
                            if (!$isNew)
                                $_value->setId($valueId);
                            $_value->save();
                        }
                    }
                }

                if (count($deleteValues)) {
                    Mage::getModel('productdesigner/option')->getResource()->deleteValues($deleteValues, $productId);
                }
            }
            if (!$product->getDesignerEnabled()) {
                Mage::getModel('productdesigner/option')->getResource()->deleteValues(null, $productId);
            }
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct() {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest() {
        return Mage::app()->getRequest();
    }

    /*
    public function saveOrder($observer) {
        $designProducts = Mage::getSingleton('core/session')->getDesignProducts();
        if (is_array($designProducts) && count($designProducts)) {
            $order = $observer->getEvent()->getOrder();
            $items = $order->getAllItems();
            foreach ($items as $item) {
                $productId = $item->getProductId();
                if (isset($designProducts[$productId])) {
                    $design = Mage::getModel('productdesigner/order_item_design');
                    $design->setOrderId($order->getId());
                    $design->setItemId($item->getId());
                    $design->setDesignId($designProducts[$productId]);
                    $design->save();
                }
            }
            Mage::getSingleton('core/session')->unsDesignProducts();
        }
        return $this;
    }
    */
    
    protected function getDesignerPath() {
        return Mage::helper('productdesigner')->getDesignerPath();
    }
      
    protected function contains($haystack, $needles) {
        foreach($needles as $needle) {
            if(strstr($haystack, $needle) !== false) {
                return true;
            }
        }
    }
}