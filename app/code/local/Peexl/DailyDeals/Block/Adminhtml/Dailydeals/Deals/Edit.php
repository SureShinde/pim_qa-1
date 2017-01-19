<?php
/**
 * @category   Peexl
 * @package    Peexl
 * @copyright  Copyright (c) 2012 Peexl Web Development (http://www.peexl.com)
 * @license    http://framework.zend.com/license/new-bsd    New BSD License
 * @version    1.0
 */
class Peexl_DailyDeals_Block_Adminhtml_Dailydeals_Deals_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
 
        $this->_objectId = 'id';
        $this->_blockGroup = 'peexl_dailydeals';
        $this->_controller = 'adminhtml_dailydeals_deals';
        $this->_mode = 'edit';
 
        $this->_addButton('save_and_continue', array(
                  'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                  'onclick' => 'saveAndContinueEdit()',
                  'class' => 'save',
        ), -100);
        $this->_updateButton('save', 'label', Mage::helper('peexl_dailydeals')->__('Save Deal'));
         $this->_updateButton('save', 'onclick', 'dealFormSubmit()');
 
         
         if (Mage::getSingleton('adminhtml/session')->getDailydealsData()) {
            $data = Mage::getSingleton('adminhtml/session')->getDailydealsData();
            Mage::getSingleton('adminhtml/session')->getDailydealsData(null);
        } elseif (Mage::registry('px_dailydeals_deal_data')) {
            $data = Mage::registry('px_dailydeals_deal_data')->getData();
        } else {
            $data = array();
        }
        
        $this->_formScripts[] = "
           
            var prod_select=new Element('tr',{'id':'prod_select'});
            var prod_name=new Element('tr',{'id':'prod_name'});
            var prod_price=new Element('tr',{'id':'prod_price'});
            var prod_qty=new Element('tr',{'id':'prod_qty'});
            var prod_qty=new Element('tr',{'id':'prod_qty'});
            var prod_id=new Element('tr',{'id':'prod_id'});
            prod_id.innerHTML = '<td></td><td ><span><input type=\"hidden\" class=\"input-text required-entry\" value=\"".(isset($data["product_id"])?$data["product_id"]:'')."\" name=\"product_id\" id=\"product_id\"></span></td>';
            var c=$('peexl_dailydeals_deal_form_general').select('table tbody')[0];
            c.insertBefore(prod_id,c.firstChild);
            c.insertBefore(prod_qty,c.firstChild);
            c.insertBefore(prod_price,c.firstChild);
            c.insertBefore(prod_name,c.firstChild);
            c.insertBefore(prod_select,c.firstChild);
            

            if ($('product_id').value!='') {
		setProduct($('product_id').value);
            } else {
                    //add Select a Product row if no product selected
                    var tdLabel = new Element('td', { 'class': 'label' });
                    tdLabel.innerHTML = '<label for=\"prod_select\">Product <span class=\"required\">*</span></label>';		
                    var tdValue = new Element('td', { \"class\": \"value\" });
                    tdValue.innerHTML = '<span><a href=\"javascript:void(0);\" onClick=\"showProductsGridTab()\">Select a Product</a></span>';

                    $('prod_select').appendChild(tdLabel);
                    $('prod_select').appendChild(tdValue);
            }
            
            function setProduct(product_id) {	
            
		//deselect previous product 	
		if ($('products_grid_radio'+$('product_id').value)) {
			$('products_grid_radio'+$('product_id').value).checked = false;
			var prevProductRow = $('products_grid_radio'+$('product_id').value).parentNode.parentNode;
			prevProductRow.removeClassName('on-mouse-over');
		}
		
		//reset product info	
		clearElement('prod_name');
		clearElement('prod_price');
		clearElement('prod_qty');
		//clearElement('prod_edit_value');
		
		//reset deal qty field and add note tag
		
                $('deal_qty').disabled = false;
		if ($('note_deal_qty')) {
			clearElement('note_deal_qty');
		} else {
			var dealQtyParent = $('deal_qty').parentNode;		    		
			var dealQtyNoteElement = new Element('p', { \"class\": \"note\", id: \"note_deal_qty\" });
			dealQtyParent.appendChild(dealQtyNoteElement);
		}
		
		//reset deal price field and add note tag
		
                $('deal_price').disabled = false;
		if ($('note_deal_price')) {
			clearElement('note_deal_price');
		} else {
			var dealPriceParent = $('deal_price').parentNode;		    		
			var dealPriceNoteElement = new Element('p', { \"class\": \"note\", id: \"note_deal_price\" });
			dealPriceParent.appendChild(dealPriceNoteElement);
		}
			
		//set new value to product_id field
		$('product_id').value = product_id;
		
		//select product		
		$('product_id_radio_'+product_id).checked = true;
		var productRow = $('product_id_radio_'+product_id).parentNode.parentNode;
		productRow.addClassName('on-mouse');
		
		//get product values
	    var selectedProductColumns = productRow.getElementsByTagName(\"td\");	 
	    var prod_name = selectedProductColumns[2].firstChild.data;	
	    var prod_type = selectedProductColumns[3].firstChild.data.replace(/^\s+|\s+$/g,\"\");	 
	    var prod_price = selectedProductColumns[6].firstChild.data;	 
	    var prod_qty = selectedProductColumns[7].firstChild.data;	
	
                
		//add Product Name 
		var tdLabel = new Element('td', { \"class\": \"label\" });
		tdLabel.innerHTML = '<label for=\"prod_name\">Product Name</label>';		
		var tdValue = new Element('td', { \"class\": \"value\" });
		tdValue.innerHTML = '<span id=\"prod_name\">' + prod_name + '</span>';		
		$('prod_name').appendChild(tdLabel);
		$('prod_name').appendChild(tdValue);
	
		//add Product Price
		var tdLabel = new Element('td', { \"class\": \"label\" });
		tdLabel.innerHTML = '<label for=\"prod_price\">Product Price</label>';		
		var tdValue = new Element('td', { \"class\": \"value\" });
		tdValue.innerHTML = '<span id=\"prod_price\">' + prod_price + '</span>';		
		$('prod_price').appendChild(tdLabel);
		$('prod_price').appendChild(tdValue);
	
		//add Product Qty
		var tdLabel = new Element('td', { \"class\": \"label\" });
		tdLabel.innerHTML = '<label for=\"prod_qty\">Product Qty</label>';		
		var tdValue = new Element('td', { \"class\": \"value\" });
		tdValue.innerHTML = '<span id=\"prod_qty\">' + prod_qty + '</span>';		
		$('prod_qty').appendChild(tdLabel);
		$('prod_qty').appendChild(tdValue);
		
		//add Product Edit options
//		var prodEditElement = new Element('p', { \"class\": \"note\", id: 'prot_note'});
//		prodEditElement.innerHTML = '<span><a href=\"javascript:void(0);\" onClick=\"showProductsTab()\">Change Product</a> || <a onclick=\"getProductUrl()\" href=\"javascript:void(0);\">Edit Product</a></span>';
//		$('prod_edit_value').appendChild(prodEditElement);
		
		//add notes and disable fields depending on Product Types
		if (prod_type=='Configurable Product') {
			clearElement('prod_qty');
		    $('deal_qty').disabled = true;		    
		    $('note_deal_qty').innerHTML = \"<span>Qty cannot be set for Configurable Products since it varies for each of it's Associated Products.</span>\";
		}
		
		if (prod_type=='Bundle Product') {
			clearElement('prod_price');
		    $('deal_price').disabled = true;
		    $('note_deal_price').innerHTML = \"<span>Price cannot be set for Bundle Products since it is Dynamic.</span>\";
		    
			clearElement('prod_qty');
		    $('deal_qty').disabled = true;
		    $('note_deal_qty').innerHTML = \"<span>Qty cannot be set for Bundle Products since it varies for each of it's Bundle Items.</span>\";
		}
		
		if (prod_type=='Grouped Product') {
		   clearElement('prod_price');
		    $('deal_price').disabled = true;
		    $('note_deal_price').innerHTML = \"<span>Price cannot be set for Grouped Products since it depends on the Associated Products.</span>\";
		    
			clearElement('prod_qty');
		    $('deal_qty').disabled = true;
		    $('note_deal_qty').innerHTML = \"<span>Qty cannot be set for Grouped Products since it varies for each of it's Associated Products.</span>\";	
		}
		clearElement('prod_select');
	}


            function productGridRowClick(grid, event){
                var rowElement = Event.findElement(event, 'tr');
                var isSelected   = Event.element(event).tagName == 'INPUT';
                if(rowElement){              
                    var checkbox = Element.getElementsBySelector(rowElement, 'input');
                    if(checkbox[0] && !checkbox[0].checked){
                        var checked = isSelected ? checkbox[0].checked : !checkbox[0].checked;
                        productGridJsObject.setCheckboxChecked(checkbox[0], checked);                        
                        setProduct(checkbox[0].value);				
                    }
                }
            }	

            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }
            

            function clearElement(id) {
		Event.stopObserving(id);	
		$(id).update();	
            }
 
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            
            function dealFormSubmit(){
                if($('productGrid_table').select('input[type=radio]:checked').length>0)
                    editForm.submit();
                else
                   alert('".Mage::helper('peexl_dailydeals')->__('Please select a product')."') ;
                
            }
  
            function showProductsGridTab(){
                    $('edit_deal_tabs_general').removeClassName('active'); 
                    $('edit_deal_tabs_products').addClassName('active');
                    Element.show('edit_deal_tabs_products_content');
                    Element.hide('edit_deal_tabs_general_content');
                    

            }
            

            productGridJsObject.rowClickCallback = productGridRowClick;
        ";
    }
 
    public function getHeaderText()
    {
        if (Mage::registry('px_dailydeals_deal_data') && Mage::registry('px_dailydeals_deal_data')->getId())
        {
            return Mage::helper('peexl_dailydeals')->__('Edit Deal "%s"', $this->htmlEscape(Mage::registry('px_dailydeals_deal_data')->getId()));
        } else {
            return Mage::helper('peexl_dailydeals')->__('New Deal');
        }
    }
 

    protected function _prepareLayout()
    {
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'));
        }
        return parent::_prepareLayout();
    }
}