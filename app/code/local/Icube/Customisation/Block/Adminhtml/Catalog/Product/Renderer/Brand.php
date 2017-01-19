<?php
class Icube_Customisation_Block_Adminhtml_Catalog_Product_Renderer_Brand extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		$attributeDetails = Mage::getSingleton("eav/config")
		    ->getAttribute("catalog_product", 'brand');
		$optionValue = $attributeDetails->getSource()->
		    getOptionText($value);
		return $optionValue;
	 
	}
 
}
?>