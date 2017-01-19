<?php
class Peexl_CategoryProduct_Model_Observer{
  
    public function catalogCategoryPrepareSave(Varien_Event_Observer $observer){
        $category=$observer->getCategory();
        $products=$category->getPostedProducts();
        foreach ($products as $pid=>$position){
            if($position=="") $products[$pid]=100;
        }
        $category->setPostedProducts($products);
     
    }
    
   
}
