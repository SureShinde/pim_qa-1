<?php
class Peexl_ProductCustomAttributes_Model_Catalog_Product_Option extends Mage_Catalog_Model_Product_Option {

    protected function _construct() {
        parent::_construct();
             
    } 



protected function _afterSave()
    {
        //parent::_afterSave();
        
        $optionId = $this->getData('option_id');
        $defaultArray = $this->getData('default') ? $this->getData('default') : array();
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        //$storeId = Mage::app()->getStore()->getId(); - not right store!
        $storeId = $this->getProduct()->getStoreId(); // right store!
                
        if (is_array($this->getData('values'))) {
            $values=array();
            foreach ($this->getData('values') as $key => $value) {
                if (isset($value['option_type_id'])) {    
                    
                    
                    if (isset($value['dependent_ids']) && $value['dependent_ids']!='') {                                
                        $dependentIds = array();
                        $dependentIdsTmp = explode(',', $value['dependent_ids']);
                        foreach ($dependentIdsTmp as $d_id) {
                            if ($this->decodeViewIGI($d_id)>0) $dependentIds[] = $this->decodeViewIGI($d_id);
                        }
                        $value['dependent_ids'] = implode(',', $dependentIds);
                    }                    
                    
                    $optionValue = array(
                        'option_id' => $optionId,
                        'sku' => $value['sku'],
                        'sort_order' => $value['sort_order'] ,                                                                     
                        'description' => $value['description'] 
                    );                    
                    if (isset($value['dependent_ids'])) $optionValue['dependent_ids'] = $value['dependent_ids'];                    

                    if (isset($value['option_type_id']) && $value['option_type_id']>0) {
                        $optionTypeId = $value['option_type_id'];
                        if ($value['is_delete']=='1') {
                            $connection->delete($tablePrefix . 'catalog_product_option_type_value', 'option_type_id = ' . $optionTypeId);                                                       
                        } else {                            
                                                        
                            $connection->update($tablePrefix . 'catalog_product_option_type_value', $optionValue, 'option_type_id = ' . $optionTypeId);

                            // update or insert price
                            if ($storeId>0) {
                                $select = $connection->select()->from($tablePrefix . 'catalog_product_option_type_price', array('COUNT(*)'))->where('option_type_id = '.$optionTypeId.' AND `store_id` = '.$storeId);
                                $isUpdate = $connection->fetchOne($select);
                            } else {
                                $isUpdate = 1;
                            }    
                            if (isset($value['price']) && isset($value['price_type'])) {
                                if ($isUpdate) {                                    
                                    $connection->update($tablePrefix . 'catalog_product_option_type_price', array('price' => $value['price'], 'price_type' => $value['price_type']), 'option_type_id = ' . $optionTypeId.' AND `store_id` = '.$storeId);
                                } else {
                                    $connection->insert($tablePrefix . 'catalog_product_option_type_price', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'price' => $value['price'], 'price_type' => $value['price_type']));
                                }
                            } elseif (isset($value['scope']['price']) && $value['scope']['price']==1 && $isUpdate && $storeId>0) {
                                $connection->delete($tablePrefix . 'catalog_product_option_type_price', 'option_type_id = ' . $optionTypeId.' AND `store_id` = '.$storeId);
                            }                            
                            
                            // update or insert title
                            if ($storeId>0) {
                                $select = $connection->select()->from($tablePrefix . 'catalog_product_option_type_title', array('COUNT(*)'))->where('option_type_id = '.$optionTypeId.' AND `store_id` = '.$storeId);
                                $isUpdate = $connection->fetchOne($select);
                            } else {
                                $isUpdate = 1;
                            } 
                            if (isset($value['title'])) {                                
                                if ($isUpdate) {                                
                                    $connection->update($tablePrefix . 'catalog_product_option_type_title', array('title' => $value['title']), 'option_type_id = ' . $optionTypeId.' AND `store_id` = '.$storeId);
                                } else {
                                    $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'title' => $value['title']));
                                }
                            } elseif (isset($value['scope']['title']) && $value['scope']['title']==1 && $isUpdate && $storeId>0) {
                                $connection->delete($tablePrefix . 'catalog_product_option_type_title', 'option_type_id = ' . $optionTypeId.' AND `store_id` = '.$storeId);
                            }     
                        }    
                    } else {                    
                        $connection->insert($tablePrefix . 'catalog_product_option_type_value', $optionValue);                
                        $optionTypeId = $connection->lastInsertId($tablePrefix . 'catalog_product_option_type_value');
                        if (isset($value['price']) && isset($value['price_type'])) {                            
                            // save not default
                            if ($storeId>0) $connection->insert($tablePrefix . 'catalog_product_option_type_price', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'price' => $value['price'], 'price_type' => $value['price_type']));
                            // save default
                            $connection->insert($tablePrefix . 'catalog_product_option_type_price', array('option_type_id' =>$optionTypeId, 'store_id'=>0, 'price' => $value['price'], 'price_type' => $value['price_type']));
                        }
                        if (isset($value['title'])) {
                            // save not default
                            if ($storeId>0) $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'title' => $value['title']));
                            // save default
                            $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' =>$optionTypeId, 'store_id'=>0, 'title' => $value['title']));
                        }    
                    }

                    if ($optionTypeId>0) {
                        $id = $this->getData('id');          
                        $thumbUrl=$this->_uploadImage("customfile-".$id."-".$key,$optionId,$optionTypeId);          
                        if ($thumbUrl) {                        
                            $connection->update($tablePrefix . 'catalog_product_option_type_value', array('image' => $thumbUrl), 'option_type_id = ' . $optionTypeId);
                        }
                    }
                    unset($value['option_type_id']);
                }    
                
                $values[$key] = $value;
                
            }            
            $this->setData('values', $values);            
        
            
        } elseif ($this->getGroupByType($this->getType()) == self::OPTION_GROUP_SELECT) {
            Mage::throwException(Mage::helper('catalog')->__('Select type options required values rows.'));
        }
        
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')) $this->cleanModelCache();
        
        Mage::dispatchEvent('model_save_after', array('object'=>$this));
        if (version_compare(Mage::getVersion(), '1.4.0', '>=')) Mage::dispatchEvent($this->_eventPrefix.'_save_after', $this->_getEventData());
        return $this;        
    }


    private function _uploadImage($file_id,$optionId,$optionTypeId){
        if(isset($_FILES[$file_id]["name"])){
                    try {
                        $uploader = new Varien_File_Uploader($file_id);
                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        //$path = Mage::getBaseDir('designer') . DS;
                        $path = Mage::getBaseDir('media') . DS . 'productcustomoptions' . DS . 'images' . DS;
                        $id = substr(md5_file($_FILES[$file_id]['tmp_name']), 0, 4) . '_' . substr(uniqid(), -4);
                        $filename = 'thumb_file_' . $id . '.' . $uploader->getFileExtension();
                        $thumbUrl = 'images/' . $filename;
                        $uploader->save($path, $filename);
                         // if ($product->getDesignerProductThumburl() && file_exists(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl())) {
                         //     unlink(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl());
                         // }                                             
                         return $thumbUrl;
                    } catch (Exception $e) {                        
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    }
                }
        return false;        
    }

    
}