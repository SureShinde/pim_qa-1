<?php

class Peexl_ProductCustomAttributes_Helper_Data extends Mage_Catalog_Helper_Data {
      
    
    
     public function getCutomOptionImageDir() {
        return Mage::getBaseDir('media') . DS . 'product_custom_option/';
    }

    public function getImageView($img){
          $block = Mage::app()->getLayout()
                 ->createBlock('core/template')
                 ->setTemplate('peexl_productcustomattributes/option_image.phtml')
                 ->addData(array('image' => $img))
                 ->toHtml(); 


        return $block;
    }

    public function getImagesThumbnailsSize(){
        return 50;
    }

     public function getSmallImageFile($fileOrig, $smallPath, $newFileName) {        
        try {
            $image = new Varien_Image($fileOrig);
            $origHeight = $image->getOriginalHeight();
            $origWidth = $image->getOriginalWidth();

            // settings
            $image->keepAspectRatio(true);
            $image->keepFrame(true);
            $image->keepTransparency(true);
            $image->constrainOnly(false);
            $image->backgroundColor(array(255, 255, 255));
            $image->quality(90);


            $width = null;
            $height = null;
            if (Mage::app()->getStore()->isAdmin()) {
                if ($origHeight > $origWidth) {
                    $height = $this->getImagesThumbnailsSize();
                } else {
                    $width = $this->getImagesThumbnailsSize();
                }
            } else {
                $configWidth = $this->getImagesThumbnailsSize();
                $configHeight = $this->getImagesThumbnailsSize();

                if ($origHeight > $origWidth) {
                    $height = $configHeight;
                } else {
                    $width = $configWidth;
                }
            }


            $image->resize($width, $height);

            $image->constrainOnly(true);
            $image->keepAspectRatio(true);
            $image->keepFrame(false);
            //$image->display();
            $image->save($smallPath, $newFileName);
        } catch (Exception $e) {
            
        }
    }
}


?>