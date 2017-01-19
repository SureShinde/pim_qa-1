<?php

class Peexl_ImportExportOrders_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function makeNew($order)
    {

        # Get PRODUCTS #
        $xml_items = '';

        //$items = $orderItems->getAllItems();
        $items = $this->_getItemsStructuredArray($order->getItemsCollection());

        foreach ($items as $item) {

            $xml_items .= "<item>";

            $xml_items .= $this->_genItemExportData($item["main_item"]);

            if (isset ($item["items"])) {
                $xml_items .= "<items>";
                foreach ($item["items"] as $sub_item) {
                    $xml_items .= "<item>";
                    $xml_items .= $this->_genItemExportData($sub_item);
                    $xml_items .= "</item>";
                }

                $xml_items .= "</items>";
            }
            $xml_items .= "</item>";
        }
        $result = "		<order>
	        <orderinfo>
	            <orderid>{$order->getRealOrderId()}</orderid>
	            <status><![CDATA[{$order->getStatus()}]]></status>
	            <state><![CDATA[{$order->getState()}]]></state>
	            <createdat>{$order->getCreatedAt()}</createdat>
	            <billingname><![CDATA[{$order->getBillingAddress()->getName()}]]></billingname>
	            <billingfirstname><![CDATA[{$order->getBillingAddress()->getFirstname()}]]></billingfirstname>
	            <billinglastname><![CDATA[{$order->getBillingAddress()->getLastname()}]]></billinglastname>
	            <billingcity><![CDATA[{$order->getBillingAddress()->getCity()}]]></billingcity>
	            <billingstreet><![CDATA[{$order->getBillingAddress()->getStreet1()}]]></billingstreet>
	            <billingcountry><![CDATA[{$order->getBillingAddress()->getCountry()}]]></billingcountry>
	            <billingphone><![CDATA[{$order->getBillingAddress()->getTelephone()}]]></billingphone>
	            <billingpostcode><![CDATA[{$order->getBillingAddress()->getPostcode()}]]></billingpostcode>";

        if ($order->getShippingAddress()) {

            $result .= "<shippingname><![CDATA[" . ($order->getShippingAddress()->getName() ? $order->getShippingAddress()->getName() : '') . "]]></shippingname>
		            <shippingfirstname><![CDATA[" . ($order->getShippingAddress()->getFirstname() ? $order->getShippingAddress()->getFirstname() : '') . "]]></shippingfirstname>
		            <shippinglastname><![CDATA[" . ($order->getShippingAddress()->getLastname() ? $order->getShippingAddress()->getLastname() : '') . "]]></shippinglastname>
		            <shippingcity><![CDATA[" . ($order->getShippingAddress()->getCity() ? $order->getShippingAddress()->getCity() : '') . "]]></shippingcity>
		            <shippingstreet><![CDATA[" . ($order->getShippingAddress()->getStreet1() ? $order->getShippingAddress()->getStreet1() : '') . "]]></shippingstreet>
		            <shippingphone><![CDATA[" . ($order->getShippingAddress()->getTelephone() ? $order->getShippingAddress()->getTelephone() : '') . "]]></shippingphone>
		            <shippingpostcode><![CDATA[" . ($order->getShippingAddress()->getPostcode() ? $order->getShippingAddress()->getPostcode() : '') . "]]></shippingpostcode>
		            <shippingcountry><![CDATA[" . ($order->getShippingAddress()->getCountry() ? $order->getShippingAddress()->getCountry() : '') . "]]></shippingcountry>";
        }

        $result .= "
	    		<shippingmethod><![CDATA[{$order->getShippingMethod()}]]></shippingmethod>
	            <paymentmethod><![CDATA[{$order->getPayment()->getMethod()}]]></paymentmethod>
	            <email><![CDATA[{$order->getCustomerEmail()}]]></email>
	            <shippingdescription><![CDATA[{$order->getShippingDescription()}]]></shippingdescription>
	        </orderinfo>
	        <items>{$xml_items}
	        </items>
	        <basetotalinvoiced>{$order->getBaseTotalInvoiced()}</basetotalinvoiced>
	        <basetaxinvoiced>{$order->getBaseTaxInvoiced()}</basetaxinvoiced>
	        <baseshippinginvoiced>{$order->getBaseShippingInvoiced()}</baseshippinginvoiced>
	        <basetotalrefunded>{$order->getBaseTotalRefunded()}</basetotalrefunded>
	        <basetaxrefunded>{$order->getBaseTaxRefunded()}</basetaxrefunded>
	        <baseshippingrefunded>{$order->getBaseShippingRefunded()}</baseshippingrefunded>
	        <basetoglobalrate>{$order->getBaseToGlobalRate()}</basetoglobalrate>
	        <shippingamount>{$order->getShippingAmount()}</shippingamount>
	        <grandtotal>{$order->getGrandTotal()}</grandtotal>
	        <basesubtotal>{$order->getBaseSubtotal()}</basesubtotal>
	        <taxamount>{$order->getTaxAmount()}</taxamount>
	        <basetaxamount>{$order->getBaseTaxAmount()}</basetaxamount>
	        <basegrandtotal>{$order->getBaseGrandTotal()}</basegrandtotal>
	        <totalpaid>{$order->getTotalPaid()}</totalpaid>
	        <baseshippingtaxamount>{$order->getBaseShippingTaxAmount()}</baseshippingtaxamount>

            <weight>{$order->getWeight()}</weight>
            <subtotal>{$order->getSubtotal()}</subtotal>
	        <basesubtotalincltax>{$order->getBaseSubtotalInclTax()}</basesubtotalincltax>
	        <totalitemcount>{$order->getTotalItemCount()}</totalitemcount>
	        <totalqtyordered>{$order->getTotalQtyOrdered()}</totalqtyordered>
	        <shippingincltax>{$order->getShippingInclTax()}</shippingincltax>
	        <subtotalincltax>{$order->getSubtotalInclTax()}</subtotalincltax>
	        <baseshippingincltax>{$order->getBaseShippingInclTax()}</baseshippingincltax>
	        <basetotalpaid>{$order->getBaseTotalPaid()}</basetotalpaid>

	    </order>\n";

        return $result;

    }

    public function _importOrders($storeId, $idSettings = 'self', $createInvoices = "no", $createShipping = "no")
    {
        $fileName = $_FILES['import_orders_file']['tmp_name'];
        $xml = simplexml_load_file($fileName);


        foreach ($xml->order as $order) {

            $orderid = $order->orderinfo->orderid;
            // fix for previous misspell in xml node
            if (!$orderid)
                $orderid = $order->orderinfo->oredid;


            $status = $order->orderinfo->status;
            $state = $order->orderinfo->state;
            $quote = Mage::getModel('sales/quote')->load($orderid);

            $items = $quote->getAllItems();
            // $quote->reserveOrderId();

            $customer = Mage::getModel('customer/customer')->setWebsiteId(1)->loadByEmail($order->orderinfo->email);
            if (!$customer->getId()) {
                $customer->setEmail($order->orderinfo->email);
            }
            $quote->setCustomer($customer);

            $store = $quote->getStore()->load($storeId);
            $quote->setStore($store);

            $billAddress = $quote->getBillingAddress();
            $billAddress->setFirstname($order->orderinfo->billingfirstname)
                ->setLastname($order->orderinfo->billinglastname)
                ->setStreet($order->orderinfo->billingstreet)
                ->setCity( $order->orderinfo->billingcity)
                ->setPostcode($order->orderinfo->billingpostcode)
                ->setCountryId($order->orderinfo->billingcountry)
                ->setTelephone($order->orderinfo->billingphone)
                ->setEmail($order->orderinfo->email);

            $billingAddress = array();
            $billingAddress['firstname'] = $order->orderinfo->billingfirstname;
            $billingAddress['lastname'] = $order->orderinfo->billinglastname;
            $billingAddress['email'] = $order->orderinfo->email;
            $billingAddress['street'] = array();
            $billingAddress['street'][0] = $order->orderinfo->billingstreet;

            $shipping = $quote->getShippingAddress();
            $shipping->setSameAsBilling(0);
            $shipping->addData($billingAddress);
            $shipping->setFirstname($order->orderinfo->shippingfirstname)
                ->setLastname( $order->orderinfo->shippinglastname)
                ->setStreet($order->orderinfo->shippingstreet)
                ->setCity($order->orderinfo->shippingcity)
                ->setPostcode($order->orderinfo->shippingpostcode)
                ->setCountryId($order->orderinfo->shippingcountry)
                ->setTelephone($order->orderinfo->shippingphone)
                ->setShippingDescription($order->orderinfo->shippingdescription);
            $shipping->implodeStreetAddress();

            $products = $order->items->item;

            $product_model = Mage::getModel('catalog/product');

            $quote->getShippingAddress()
                ->setShippingMethod($order->orderinfo->shippingmethod);

            $quote->getShippingAddress()->setCollectShippingRates(false);

            $quote->save();
            $quoteId = $quote->getId();

            $items = $quote->getShippingAddress()->getAllItems();
            $quote->collectTotals();

            // if we have an order ID then we check it, else use a new one


            if ($idSettings == 'file') {
                if (!$quote->getResource()->isOrderIncrementIdUsed($orderid)) {
                    $quote->setReservedOrderId($orderid);
                } else {
                    $quote->reserveOrderId();
                }
            } else {
                $quote->reserveOrderId();
            }

            $quotePaymentObj = $quote->getPayment();
            $quotePaymentObj->setMethod($order->orderinfo->paymentmethod);
            $quotePaymentObj->setBaseAmountOrdered($order->basesubtotal);
            $quotePaymentObj->setAmountOrdered($order->subtotal);
            $quote->setPayment($quotePaymentObj);


            $convertquote = Mage::getSingleton('sales/convert_quote');
            $orderObj = $convertquote->addressToOrder($quote->getShippingAddress());
            $orderObj->setBillingAddress($convertquote->addressToOrderAddress($quote->getBillingAddress()));
            $orderObj->setShippingAddress($convertquote->addressToOrderAddress($quote->getShippingAddress()));
            $orderObj->setPayment($convertquote->paymentToOrderPayment($quotePaymentObj));


            $orderObj->setCanShipPartiallyItem(false);

            $orderObj->setCustomerNote($order->customernote);


            $orderObj->setSubtotal($order->subtotal);
            $orderObj->setBaseSubtotal($order->basesubtotal);

            $orderObj->setShippingAmount($order->shippingamount); /**/
            $orderObj->setBaseShippingAmount($order->shippingamount);
            $orderObj->setBaseShippingTaxAmount($order->baseshippingtaxamount);

            $orderObj->setBaseTaxAmount($order->basetaxamount); /**/
            $orderObj->setGrandTotal($order->grandtotal);
            $orderObj->setBaseGrandTotal($order->basegrandtotal); /**/


            $orderObj->setWeight($order->weight);
            $orderObj->setTotalItemCount($order->totalitemcount);
            $orderObj->setTotalQtyOrdered($order->totalqtyordered);
            $orderObj->setBaseShippingInclTax($order->baseshippingincltax);
            $orderObj->setShippingInclTax($order->shippingincltax);
            $orderObj->setBaseSubtotalInclTax($order->basesubtotalincltax);
            $orderObj->setSubtotalInclTax($order->subtotalincltax);

            $orderObj->setData('state', $state);
            $history = $orderObj->addStatusHistoryComment('Order marked as complete automatically.', false);
            $history->setIsCustomerNotified(false);

            $orderObj->place();
            $orderObj->save();

            foreach ($products as $pm) {

                $orderItem = Mage::getModel('sales/order_item');
                $orderItem->setName($pm->name);
                $orderItem->setOrderId($orderObj->getId());
                $productId = Mage::getModel('catalog/product')->getIdBySku($pm->sku);
                if (!$productId)
                    $productId = $pm->id;
                $orderItem->setProductId($productId);
                $orderItem->setSku($pm->sku);
                $orderItem->setPrice($pm->price);
                $orderItem->setQtyOrdered($pm->qty);

                $orderItem->setOriginalPrice($pm->originalprice);
                $orderItem->setBasePrice($pm->baseprice);
                $orderItem->setBaseOriginalPrice($pm->baseoriginalprice);
                $orderItem->setBaseRowTotal($pm->baserowtotal);
                $orderItem->setRowTotal($pm->rowtotal);

                $orderItem->setPriceInclTax($pm->priceincltax);
                $orderItem->setBasePriceInclTax($pm->basepriceincltax);
                $orderItem->setRowTotalInclTax($pm->rowtototalincltax);
                $orderItem->setBaseRowTotalInclTax($pm->baserowtotoalincltax);
                $orderItem->setWeight($pm->weight);
                $orderItem->setRowWeight($pm->rowweight);

                $orderItem->setSubTotal($pm->subtotal);
                $orderItem->setProductType($pm->product_type);
                $orderItem->setQuoteItemId(0);
                $orderItem->setQuoteParentItemId(NULL);
                $orderItem->setProductOptions(unserialize($pm->product_options));

                $orderItem->save();
                if ($pm->items) {
                    foreach ($pm->items->item as $_pm) {

                        $orderSubItem = Mage::getModel('sales/order_item');
                        $orderSubItem->setOrderId($orderObj->getId());
                        $orderSubItem->setName($_pm->name);
                        $productId = Mage::getModel('catalog/product')->getIdBySku($_pm->sku);
                        if (!$productId)
                            $productId = $_pm->id;
                        $orderSubItem->setOrderId($orderObj->getId());
                        $orderSubItem->setProductId($productId);
                        $orderSubItem->setSku($_pm->sku);
                        $orderSubItem->setProductType($_pm->product_type);
                        $orderSubItem->setPrice($_pm->price);
                        $orderSubItem->setQtyOrdered($_pm->qty);

                        $orderSubItem->setOriginalPrice($_pm->originalprice);
                        $orderSubItem->setBasePrice($_pm->baseprice);
                        $orderSubItem->setBaseOriginalPrice($_pm->baseoriginalprice);
                        $orderSubItem->setBaseRowTotal($_pm->baserowtotal);
                        $orderSubItem->setRowTotal($_pm->rowtotal);

                        $orderSubItem->setPriceInclTax($_pm->priceincltax);
                        $orderSubItem->setBasePriceInclTax($_pm->basepriceincltax);
                        $orderSubItem->setRowTotalInclTax($_pm->rowtototalincltax);
                        $orderSubItem->setBaseRowTotalInclTax($_pm->baserowtotoalincltax);
                        $orderSubItem->setWeight($_pm->weight);
                        $orderSubItem->setRowWeight($_pm->rowweight);


                        $orderSubItem->setSubTotal($_pm->subtotal);
                        $orderSubItem->setParentItemId($orderItem->getItemId());
                        $orderSubItem->setQuoteItemId(0);
                        $orderSubItem->setQuoteParentItemId(NULL);
                        $orderSubItem->setProductOptions(unserialize($_pm->product_options));

                        $orderSubItem->save();
                    }

                }
            }

            Mage::log($orderObj->getState(), false, "Peexl_ImportExportOrders_Helper_Data.log");

            if ($createInvoices == "yes")
                $this->makeOrderInvoice($orderObj);

            if ($createShipping == "yes") {
                $this->makeOrderShipment($orderObj);
            }

            $quote->setIsActive(false);
            $quote->delete();

        }

    }


    public function countOrders($storeId)
    {
        return Mage::getModel('sales/order')->getCollection()->addFieldToFilter('store_id', $storeId)->getSize();
    }

    private function _genItemExportData($item)
    {
        $productOptions = serialize($item->getProductOptions());
        return "
	                <id><![CDATA[{$item->getId()}]]></id>
	                <product_id><![CDATA[{$item->getProductId()}]]></product_id>
	                <sku><![CDATA[{$item->getSku()}]]></sku>
	                <name><![CDATA[{$item->getName()}]]></name>
	                <qty><![CDATA[{$item->getQtyOrdered()}]]></qty>
	                <price>{$item->getPrice()}</price>
	                <rowtotal>{$item->getRowTotal()}</rowtotal>

                    <originalprice>{$item->getOriginalPrice()}</originalprice>
                    <baseprice>{$item->getBasePrice()}</baseprice>
                    <baseoriginalprice>{$item->getOriginalPrice()}</baseoriginalprice>
	                <baserowtotal>{$item->getBaseRowTotal()}</baserowtotal>
	                <priceincltax>{$item->getPriceInclTax()}</priceincltax>
	                <basepriceincltax>{$item->getBasePriceInclTax()}</basepriceincltax>
	                <rowtototalincltax>{$item->getRowTotalInclTax()}</rowtototalincltax>
	                <baserowtotoalincltax>{$item->getBaseRowTotalIncltax()}</baserowtotoalincltax>
	                <weight>{$item->getWeight()}</weight>
	                <rowweight>{$item->getRowWeight()}</rowweight>
	                <product_type>{$item->getProductType()}</product_type>
	                <parent_item_id>{$item->getParentItemId()}</parent_item_id>
	                <product_options>{$productOptions}</product_options>
	            ";
    }

    private function _getItemsStructuredArray($items)
    {
        $structuredArray = array();
        foreach ($items as $item) {
            if (!$item->getParentItemId()) {
                $structuredArray[$item->getId()]["main_item"] = $item;

            } else
                $structuredArray[$item->getParentItemId()]["items"][] = $item;

        }

        return $structuredArray;
    }


    protected function makeOrderInvoice($_order)
    {
        $order = Mage::getModel('sales/order')->load($_order->getId());
        try {


            if (!$order->canInvoice()) {
                Mage::Log('Order ' . $order->getItd() . ': Cannot create an invoice.', null, 'invoice.log');
            }


            $items = $order->getItemsCollection();

            $qtys = array(); //this will be used for processing the invoice

            foreach ($items as $item) {

                $qtys[$item->getId()] = $item->getQtyOrdered();

            }

            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice($qtys);

            $amount = $invoice->getGrandTotal();
            $invoice->setRequestedCaptureCase(
                Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE
            );
            $invoice->register()->pay();
            $invoice->getOrder()->setIsInProcess(true);

            $history = $invoice->getOrder()->addStatusHistoryComment(
                'Partial amount of $' . $amount . ' captured automatically.', false
            );

            $history->setIsCustomerNotified(true);

            $order->save();

            Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();

        } catch (Mage_Core_Exception $e) {
            Mage::Log($e, null, "invoice.log");
        }
    }

    public function makeOrderShipment($_order)
    {
        $order = Mage::getModel('sales/order')->load($_order->getId());
        $items = $order->getItemsCollection();

        $qtys = array(); //this will be used for processing the invoice

        foreach ($items as $item) {
            $qtys[$item->getId()] = $item->getQtyOrdered();
        }


        if ($order->canShip()) {

            $shipment = $order->prepareShipment($qtys);
            if ($shipment) {
                $shipment->register();
                $shipment->addComment('Shippment created by Import/Export Orders Module');
                $shipment->getOrder()->setIsInProcess(true);
                try {
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();

                } catch (Mage_Core_Exception $e) {
                    // var_dump($e);
                }

            }

        }

    }
}