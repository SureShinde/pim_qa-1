<?php
class Icube_Pim_Helper_Generate extends Mage_Core_Helper_Abstract
{
	public function generateSKU()
	{
		$oldStoreId = Mage::app()->getStore()->getId();
		$collection = Mage::getModel('catalog/product')->getCollection()
	            ->addAttributeToFilter('klikmro_sku', array('eq' => 'klikmro_sku'));
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		foreach ($collection as $col)
		{
			 Mage::log('Begin Update Product:'.$col->getSku(), null, 'klikmro_sku.log');
			 $code = '';
			 $categories = Mage::getModel('catalog/category')
					->getCollection()
					->addAttributeToSelect('category_code')
					->addAttributeToFilter('level', array('eq' => 3))
					->addAttributeToFilter('entity_id', array('in' => $col->getCategoryIds()));
			foreach ($categories as $cat)
			{
				$code = $cat->getCategoryCode();
			}
			$sku = Mage::getModel('pim/skugenerator')->generateSKU($code);
			Mage::log('Klikmro SKU:'.$sku, null, 'klikmro_sku.log');
			echo($col->getSku().' klikmrosku:'.$sku);
			$product = Mage::getModel('catalog/product')->load($col->getId())
			->setKlikmroSku($sku)->save();
		}
		Mage::app()->setCurrentStore($oldStoreId);
	}
}