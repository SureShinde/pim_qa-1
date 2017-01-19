<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Product_Edit extends Mage_Core_Block_Template
{
	protected $_product;
	protected $_attributes;
	
	public function getProduct() 
	{
        if(!$this->_product)
        {
	        $this->_product = Mage::registry('vendor_product');
        }
        return $this->_product;
    }
	
	public function getAttributes()
	{
        $attributesCollection = Mage::getModel('catalog/product_attribute_api')->items($this->getProduct()->getAttributeSetId());
        
        return $attributesCollection;
    }
    
    public function getProductAttributes()
    {
        $data = array();
        if(!$this->_attributes)
        {
	        $attributes = $this->getProduct()->getAttributes();
	        foreach ($attributes as $attribute)
	        {
	            $value = $attribute->getFrontend()->getValue($this->getProduct());
	            $data[$attribute->getAttributeCode()] = $value;
	        }
	        $this->_attributes = $data;
        }
        
        return $this->_attributes;
    }
    
    public function getAttributeHtml($attribute)
    {
	    $data = $this->getProductAttributes();
        $frontend = $attribute->getFrontend();

        switch($frontend->getInputType())
        {
            case 'weight' :
                $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;
        return '<input type="text" value="'.$value.'" name="' . $attribute->getAttributeCode() . '" class="' . $attribute->getFrontend()->getClass() . ' form-control">';
            break;
            
            case 'text' :
                $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;
        return '<input type="text" value="'.$value.'" name="' . $attribute->getAttributeCode() . '" class="' . $attribute->getFrontend()->getClass() . ' form-control">';
            break;
            
            case 'textarea' :
                $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;
        return '<textarea class="form-control" name="' . $attribute->getAttributeCode() . '">'.$value.'</textarea>';
            break;
            
            case 'price' :
                $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;
        return '<input type="text" value="'.$value.'" name="' . $attribute->getAttributeCode() . '" class="form-control ' . $attribute->getFrontend()->getClass() . '">';
            break;
            
            case 'date' :
                $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;
        return '<input type="text" value="'.$value.'" name="' . $attribute->getAttributeCode() . '" value="'.$value.'" class="datepicker ' . $attribute->getFrontend()->getClass() . '">';
            break;
            
            case 'select' :
                return $this->_getSelectField($attribute, $data);
            break;
            
            case 'multiselect' :
                return $this->_getSelectField($attribute, $data, true);
            break;

            case 'boolean' :
                return $this->_getBooleanField($attribute, $data, true);
            break;
            
            default :
                    return $frontend->getInputType();
            break;
        }
    }

    protected function _getSelectField($attribute, $data, $isMultiple = false)
    {
        $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : null;

        // if(strstr($value, ', ')) {
        //     $value = explode(', ', $value);
        // }

        $isMultiple = ($isMultiple) ? " multiple" : "";
        $isMultipleStyle = ($isMultiple) ? " height:150px;" : "";
        $name = $attribute->getAttributeCode();
        $name .= ($isMultiple) ? "[]" : "";

        $html = '<select name="' . $name . '" style="'.$isMultipleStyle.'" class="form-control '. $attribute->getFrontend()->getClass() . '"'.$isMultiple.'>';
        $allOptions = $attribute->getSource()->getAllOptions(false);
        $html .= '<option value=""></option>';
        
        foreach($allOptions AS $option) {
            if($option['value'] == '') continue;

            $selected = (($value == $option['value'] ||
                    (is_array($value) && in_array($option['value'], $value))) ||
                ($value == $option['label'] || (is_array($value) && in_array($option['label'], $value))));

            $html .= '<option value="' . $option['value'] . '" '.($selected ? '  selected="selected"' : '').'>'.$option['label'].'</option>';
        }

        $html .= '</select>';
        return $html;
    }

    protected function _getBooleanField($attribute, $data)
    {
        $value = isset($data[$attribute->getAttributeCode()]) ? $data[$attribute->getAttributeCode()] : $attribute->getDefaultValue();
        $html = '<select name="' . $attribute->getAttributeCode() . '" class="form-control '. $attribute->getFrontend()->getClass() . '">';
        $allOptions = $attribute->getSource()->getAllOptions();

        foreach($allOptions as $option) {
            $selected = (($value == $option['value'] ||
                    (is_array($value) && in_array($option['value'], $value))) ||
                ($value == $option['label'] || (is_array($value) && in_array($option['label'], $value))));
            $html .= '<option value="'.$option['value'].'" '.(($selected) ? ' selected="selected"' : '').'>'.$option['label'].'</option>';
        }

        $html .= '</select>';
        return $html;
    }
    
    public function setSelectedCategories($categories) {
        $this->_selectedCategories = $categories;
    }
    
    public function getCategories()
    {
        $parent     = Mage::app()->getStore()->getRootCategoryId();
        $category = Mage::getModel('catalog/category');

        if (!$category->checkId($parent)) {
            return new Varien_Data_Collection();
        }

       $categories = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToFilter('entity_id', array('neq' => $parent))
            ->addAttributeToSort('path');

        return $categories;
    }
    
    public function getCategoryNodes()
    {
        $str = '';
        $categories = $this->getCategories();

        foreach($categories as $category) {
            $cat = Mage::getModel('catalog/category')->load($category->getEntityId());

            //if($cat->getData('available_for_supplier') === 0) continue;

            $str .= $this->_renderCategory($cat);
        }

        return $str;
    }
    
    protected function _renderCategory($cat) {
	    $hide = "";
	    
	    if($cat->getLevel() != 2){
		    $hide = "display:none;";
	    }
	    
        $str = '<li class="level-'.$cat->getLevel().'" style="margin-left:' . (15*$cat->getLevel()).'px;'.$hide.'">';
        $str .= '<input class="check-level parent-'.$cat->getParentId().'" parent="'.$cat->getParentId().'" categoryid="'.$cat->getEntityId().'" type="checkbox" name="category_ids[]" value="'.$cat->getId().'" '.(in_array($cat->getId(), $this->getProduct()->getCategoryIds()) ? ' checked' : '') .'/> ';
        $str .= $cat->getName();
        $str .= '</li>';
        return $str;
    }
      
}