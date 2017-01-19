<?php
class Peexl_SpecialCategories_AjaxController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		echo "123";
	}
	public function getPriceAction() {
		$total = Mage::helper ( 'checkout/cart' )->getQuote ()->getGrandTotal ();
		$ids = Mage::app ()->getRequest ()->getParam ( 'nearly_there_ids' );
		if (is_array ( $ids )) {
			foreach ( $ids as $prodid ) {
				$product = Mage::getModel ( 'catalog/product' )->load ( $prodid );
				$total += $product->getPrice ();
			}
		}
		// $prod = Mage::getModel('catalog/product')->load($product->getId());
		echo json_encode ( array (
				"subtotal" => Mage::helper ( 'core' )->formatPrice ( $total, false ),
                                "url"=>Mage::getUrl('checkout/cart')
		) );
	}
	public function addProductsAction() {

 		$ids = Mage::app ()->getRequest ()->getParam ( 'nearly_there_ids' );

		if (is_array ( $ids )) {
			$cart = Mage::getSingleton('checkout/cart');
			foreach ( $ids as $prodid ) {
				$product = Mage::getModel ( 'catalog/product' )->load ( $prodid );
				
				$cart->addProduct ( $product, array (
						'qty' => '1' 
				) );								
			}
		}
		
		$cart->save ();
		
		echo json_encode(array("success"=>true,"url"=>Mage::getUrl('checkout/cart')));
	}
}
