<?php

class Peexl_ProductCustomAttributes_Model_Observer {

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

  public function saveProductCustomOptions(Varien_Event_Observer $observer){
if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
        
        // $product = $observer->getEvent()->getProduct();
        // $productId = $product->getId();        
        // $options = $product->getOptions();
       
       
        // Mage::log($product->getProductOptions(), null, 'custom.log');
        // Mage::log($_FILES, null, 'custom.log');
        // $options = $product->getProductOptions();

        // //var_dump($options);die();
        // foreach ($options as $optionId => $vals) {

        //     foreach ($vals["values"] as $selId => $row) {        

        //      if(isset($_FILES["customfile-".$optionId."-".$selId]["name"])){
        //             try {
        //                 $uploader = new Varien_File_Uploader("customfile-".$optionId."-".$selId);
        //                 $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
        //                 $uploader->setAllowRenameFiles(false);
        //                 $uploader->setFilesDispersion(false);
        //                 //$path = Mage::getBaseDir('designer') . DS;
        //                 $path = Mage::getBaseDir('media') . DS . 'productcustomoptions' . DS . 'images' . DS;
        //                 $id = substr(md5_file($_FILES["customfile-".$optionId."-".$selId]['tmp_name']), 0, 4) . '_' . substr(uniqid(), -4);
        //                 $filename = 'thumb_file_' . $id . '.' . $uploader->getFileExtension();
        //                 $thumbUrl = 'images/' . $filename;
        //                 $uploader->save($path, $filename);
        //                  // if ($product->getDesignerProductThumburl() && file_exists(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl())) {
        //                  //     unlink(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl());
        //                  // }                        
        //                 $options[$optionId]["values"][$selId]["image"]=$thumbUrl;
        //                 $product->setProductOptions($options);
        //                  //$product->setCanSaveCustomOptions(true); 
        //                  $product->save();
        //             } catch (Exception $e) {
        //                 echo $e; die();
        //                 Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        //             }
        //         }
        //     }
        // }
        
  /*      foreach ($options as $o) {

            foreach ($o->getValues() as $v) {                              
              Mage::log($o->getId().' - '.$v->getId(), null, 'custom.log');
              if(isset($_FILES["customfile-".$o->getId()."-".$v->getId()]["name"])){
                    try {
                        $uploader = new Varien_File_Uploader("customfile-".$o->getId()."-".$v->getId());
                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        //$path = Mage::getBaseDir('designer') . DS;
                        $path = Mage::getBaseDir('media') . DS . 'productcustomoptions' . DS . 'images' . DS;
                        $id = substr(md5_file($_FILES["customfile-".$o->getId()."-".$v->getId()]['tmp_name']), 0, 4) . '_' . substr(uniqid(), -4);
                        $filename = 'thumb_file_' . $id . '.' . $uploader->getFileExtension();
                        $thumbUrl = 'images/' . $filename;
                        $uploader->save($path, $filename);
                         // if ($product->getDesignerProductThumburl() && file_exists(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl())) {
                         //     unlink(Mage::getBaseDir() . DS . 'designer' . DS . $product->getDesignerProductThumburl());
                         // } 
                         $v->setImage($thumbUrl);
                         $v->save();                         
                    } catch (Exception $e) {                        
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    }
                }
            }
        }  */  


        }
  }

    public function saveProductCustomOptionsData(Varien_Event_Observer $observer){

  }

}