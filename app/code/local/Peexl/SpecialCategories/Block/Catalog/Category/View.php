<?php

class Peexl_SpecialCategories_Block_Catalog_Category_View extends Mage_Catalog_Block_Category_View {

//    protected function _prepareLayout() {
//        parent::_prepareLayout();               
//    }

    public function getProductListHtml() {
        $curCategory = $this->getCurrentCategory();
        if ($curCategory->is_special_category) {
           return $this->getChildHtml('peexl.specialcategories');         
            
        } else {
            return parent::getProductListHtml();
        }
        //        
    }

}

?>
