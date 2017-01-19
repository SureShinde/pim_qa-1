<?php
class Icube_Customisation_Block_Adminhtml_Catalog_Product_Renderer_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$value =  $row->getData($this->getColumn()->getIndex());
		
		$attributeDetails = Mage::getSingleton("eav/config")
		    				->getAttribute("catalog_product", 'vendor_id');
		$optionValue = $attributeDetails->getSource()
						->getOptionText($value);
		
		$vendor = Mage::getModel('pim/vendor')->load($optionValue,'vendor_id');
		$name = $vendor->getVendorName();
		
		return $name;
	 
	}
 
}
?>