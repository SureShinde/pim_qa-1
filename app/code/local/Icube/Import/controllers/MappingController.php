<?php

class Icube_Import_MappingController extends Mage_Core_Controller_Front_Action
{   
    public function updateAttributeSetAction()
    {
	    Mage::helper('import/mapping')->updateAttributeSet();
    }
    
    public function checkAttributeSetNotMappedAction()
    {
	    $readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
	    $query = "select * from `icube_mapping_attribute_set` i right join `eav_attribute_set` e on i.`ah_code` = e.`attribute_set_name` where i.id is null";
	    
	    $cols = $readConnection->query($query);
	    foreach($cols as $col)
		{
			echo($col['attribute_set_id'].'-'.$col['attribute_set_name']);
			echo('<br>');
			
		}
	    
	}

}