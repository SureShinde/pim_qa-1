<?php
class Icube_Import_Helper_Mapping extends Mage_Core_Helper_Abstract
{
	public function updateAttributeSet()
    {
	    echo('<pre>');
	    echo('Start.. update attribute set<br>');
	    try {

	      	$writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
	      	$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
            
            $attSetNameFoRename = '';
            $setIdForRename = '';
            $checkAttrName = '';
            $checkAttrId = '';

            $queryMap = "select * from icube_mapping_attribute_set i
join eav_attribute_set e on i.ah_code = e.attribute_set_name order by i.attribute_set,i.id";
			echo($queryMap.'<br>');
			$cols = $readConnection->query($queryMap);

			foreach($cols as $col)
			{
				$attSetNameFoRename = $col['attribute_set'];
				$setIdForRename = $col['attribute_set_id'];
				
				$queryCheck = "SELECT attribute_set_id,attribute_set_name from eav_attribute_set WHERE attribute_set_name = '$attSetNameFoRename' LIMIT 1";
				echo($queryCheck.'<br>');
				Mage::log($queryCheck, null, "attributeset-map-query.log");
				
				$check = $readConnection->query($queryCheck);
				
				foreach($check as $c)
				{
					$checkAttrName = $c['attribute_set_name'];
					$checkAttrId = $c['attribute_set_id'];
				}
				
				if($attSetNameFoRename == $checkAttrName)
				{
					$setIdForRename = $checkAttrId;
					$ahToRename = $col['attribute_set_id'];
					// go to product entity : replace set id into $setIdForRename
					$queryProduct = "SELECT * from catalog_product_entity WHERE attribute_set_id = $ahToRename";
					echo($queryProduct.'<br>');
					Mage::log($queryProduct, null, "attributeset-map-query.log");
					
					$products = $readConnection->query($queryProduct);
					foreach($products as $product)
					{
						// rename into $setIdForRename
						$productSetId = $product['attribute_set_id'];
						$sku = $product['sku'];
						
						Mage::log('Product Set ID:'.$productSetId.' SKU:'.$sku.' Rename into Set ID:'.$setIdForRename, null, "attributeset-map-query.log");
						
						$queryInsert = "INSERT INTO icube_attribute_set_renamed (attribute_set_id, sku, attribute_set_id_new) VALUES ($productSetId, '$sku', $setIdForRename)";
						$writeConnection->query($queryInsert);
						
						echo($queryInsert.'<br>');
						Mage::log($queryInsert, null, "attributeset-map-query.log");
						
					}
					
					$queryUpdate = "UPDATE catalog_product_entity SET attribute_set_id = $setIdForRename WHERE attribute_set_id = $ahToRename";
					echo($queryUpdate.'<br>');
					Mage::log($queryUpdate, null, "attributeset-map-query.log");
					$writeConnection->query($queryUpdate);	
					
				}
				else
				{
					// RENAME ATTRIBUTE SET NAME
					$queryUpdateSet = "UPDATE eav_attribute_set SET attribute_set_name = '$attSetNameFoRename' WHERE attribute_set_id = $setIdForRename";
					echo($queryUpdateSet.'<br>');
					Mage::log($queryUpdateSet, null, "attributeset-map-query.log");
		
					$writeConnection->query($queryUpdateSet);
					Mage::log('Rename Attribute Set '.$col['attribute_set_name'], null, "attributeset-map-query.log");
				}
							
			}
	    } catch (Exception $e) {
	        Mage::log($e->getMessage());
	    }
	    echo('<br>Finished');
    }
}