<?php
class Icube_Order_Model_Order_Api extends Mage_Api_Model_Resource_Abstract
{

    public function create($data)
    {
        $xml = simplexml_load_string($data);


        foreach ($xml->order as $order) {

            $orderid = $order->orderinfo->vendororder;
            // fix for previous misspell in xml node
            if (!$orderid)
                $orderid = $order->orderinfo->oredid;


            $status = $order->orderinfo->status;
            $state = $order->orderinfo->state;
            $quote = Mage::getModel('sales/quote')->load($orderid);

            $items = $quote->getAllItems();


            $customer = Mage::getModel('customer/customer')->setWebsiteId(1)->loadByEmail($order->orderinfo->email);
            if (!$customer->getId()) {
                $customer->setEmail($order->orderinfo->email);
            }
            $quote->setCustomer($customer);

            $store = $quote->getStore()->load(Mage::app()->getStore());
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


            // if ($this->getIndexImport() == 'file') {
                if (!$quote->getResource()->isOrderIncrementIdUsed($orderid)) {
                    $quote->setReservedOrderId($orderid);
                } else {
                    $quote->reserveOrderId();
                }
            // } else {
            //     $quote->reserveOrderId();
            // }

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

            $orderObj->setData('vendor_id', $order->orderinfo->vendor);
            $orderObj->setCustomerFirstname($order->orderinfo->firstname);
            $orderObj->setCustomerLastname($order->orderinfo->lastname);
            

            $orderObj->setData('state', $state);
            $history = $orderObj->addStatusHistoryComment('Order marked as complete automatically.', false);
            $history->setIsCustomerNotified(false);

            $orderObj->place();
            $orderObj->save();

            $subtotal = 0;
            $basesubtotal = 0;
            $basetaxamount = 0;
            $grandtotal = 0;
            $basegrandtotal = 0;
            $itemarr = array();
            foreach ($products as $pm) {

                $orderItem = Mage::getModel('sales/order_item');
                $orderItem->setName($pm->name);
                $orderItem->setOrderId($orderObj->getId());
                $product = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToSelect('*') 
                            ->addAttributeToFilter('klikmro_sku', $pm->klikmrosku)
                            ->getFirstItem();
                $productId = $product->getId();
                if (!$productId)
                    $productId = $pm->id;
                $orderItem->setProductId($productId);
                $orderItem->setSku($product->getSku());
                $orderItem->setPrice($pm->price);
                $orderItem->setQtyOrdered($pm->qty);

                $orderItem->setOriginalPrice($pm->originalprice);
                $orderItem->setBasePrice($pm->baseprice);
                $orderItem->setBaseOriginalPrice($pm->baseoriginalprice);
                $orderItem->setBaseRowTotal($pm->baserowtotal);
                $orderItem->setRowTotal($pm->rowtotal);

                $orderItem->setDiscountAmount($pm->discountamount);
                $orderItem->setBaseDiscountAmount($pm->basediscountamount);
                $orderItem->setDiscountPercent($pm->discountpercent);
                $orderItem->setTaxAmount($pm->taxamount);
                $orderItem->setBaseTaxAmount($pm->basetaxamount);
                $orderItem->setTaxPercent($pm->taxpercent);

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

                $subtotal += $pm->rowtotal;
                $basesubtotal += $pm->baserowtotal;
                $grandtotal += ($pm->rowtotal - $pm->discountamount + $pm->taxamount);
                $basegrandtotal += ($pm->baserowtotal - $pm->basediscountamount + $pm->basetaxamount);
                $basetaxamount += $pm->basetaxamount;
                $totalitemcount += 1;
                $totalqtyordered += $pm->qty;
                $totalweight += $pm->rowweight;

                $orderItem->save();
                $itemarr[] = $orderItem->getId();
                if ($pm->items) {
                    foreach ($pm->items->item as $_pm) {

                        $orderSubItem = Mage::getModel('sales/order_item');
                        $orderSubItem->setOrderId($orderObj->getId());
                        $orderSubItem->setName($_pm->name);
                        $product = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToSelect('*') 
                            ->addAttributeToFilter('klikmro_sku', $_pm->klikmrosku)
                            ->getFirstItem();
                        $productId = $product->getId();
                        if (!$productId)
                            $productId = $_pm->id;
                        $orderSubItem->setOrderId($orderObj->getId());
                        $orderSubItem->setProductId($productId);
                        $orderSubItem->setSku($product->getSku());
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

            $orderObj->setSubtotal($subtotal);
            $orderObj->setBaseSubtotal($basesubtotal);

            $orderObj->setShippingAmount($order->shippingamount); /**/
            $orderObj->setBaseShippingAmount($order->shippingamount);
            $orderObj->setBaseShippingTaxAmount($order->baseshippingtaxamount);

            $orderObj->setBaseTaxAmount($basetaxamount); /**/
            $orderObj->setGrandTotal($grandtotal);
            $orderObj->setBaseGrandTotal($basegrandtotal); /**/


            $orderObj->setWeight($totalweight);
            $orderObj->setTotalItemCount($totalitemcount);
            $orderObj->setTotalQtyOrdered($totalqtyordered);
            $orderObj->setBaseShippingInclTax($order->baseshippingincltax);
            $orderObj->setShippingInclTax($order->shippingincltax);
            $orderObj->setBaseSubtotalInclTax($order->basesubtotalincltax);
            $orderObj->setSubtotalInclTax($order->subtotalincltax);
            $orderObj->save();

            Mage::log($orderObj->getState().' '.$orderObj->getIncrementId(), false, "orderimport.log");

            // if ($this->getInvoiceCreate() == "yes")
            //     $this->makeOrderInvoice($orderObj);

            // if ($this->getShipmentCreate() == "yes") {
            //     $this->makeOrderShipment($orderObj);
            // }

            $quote->setIsActive(false);
            $quote->delete();
			
			// Load vendorId : updated after modify the column ID
            // $vendor = Mage::getModel('pim/vendor')->load($order->orderinfo->vendor);
            $vendor = Mage::getModel('pim/vendor')->load($order->orderinfo->vendor, 'vendor_id');
            if ($vendor->getId()) {
                $orderObj->getSendConfirmation(null);
                $paymentBlock = Mage::helper('payment')->getInfoBlock($orderObj->getPayment())
                    ->setIsSecureMode(true);
                $paymentBlock->getMethod()->setStore($orderObj->getStore()->getId());
                $paymentBlockHtml = $paymentBlock->toHtml();

                $mailer = Mage::getModel('core/email_template_mailer');
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($vendor->getEmail(), $vendor->getVendorName());
                $mailer->addEmailInfo($emailInfo);
                $mailer->setSender(Mage::getStoreConfig('sales_email/order_comment/identity', $orderObj->getStore()->getId()));
                $mailer->setStoreId($orderObj->getStore()->getId());
                $mailer->setTemplateId('sales_icube_vendorOrderNotifier_email_template');
                $mailer->setTemplateParams(array(
                    'order' => $orderObj,
                    'itemarr'      => $itemarr
                ));

                $mailer->send();

            }
        }
    }
}