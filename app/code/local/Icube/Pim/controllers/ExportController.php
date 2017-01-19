<?php
class Icube_Pim_ExportController extends Mage_Core_Controller_Front_Action
{
	public function exportProductToMroAction()
	{
		return Mage::helper('pim')->exportProductToMro();
	}
	
	public function exportProductImageToMroAction()
	{
		return Mage::helper('pim')->exportProductImageToMro();
	}

	public function exportProductGalleryToMroAction()
	{
		return Mage::helper('pim')->exportProductGalleryToMro();
	}

}
