<?php
 
class Icube_Pim_Block_Adminhtml_Vendor_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{    
    protected function _prepareForm()
    {
	    $data = array();
        $form = new Varien_Data_Form(
        		array(
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                    'method' => 'post',
					)
        );
        
        if ( Mage::getSingleton('adminhtml/session')->getVendorData() )
        {
            $data = Mage::getSingleton('adminhtml/session')->getVendorData();
            Mage::getSingleton('adminhtml/session')->setVendorData(null);
        } elseif ( Mage::registry('vendor_data') ) {
            $data = Mage::registry('vendor_data');
        }
        

        $fieldset = $form->addFieldset('vendor_fieldset', array('legend'=>Mage::helper('pim')->__('Vendor information')));
		
		if($data['id'])
	    {
			$fieldset->addField('vendor_id', 'text', array(
	            'label'     => Mage::helper('pim')->__('Vendor ID'),
	            'readonly' => true,
	            'name'      => 'vendor_id',
	        ));
        }
       else
       {
	      $fieldset->addField('vendor_id', 'text', array(
            'label'     => Mage::helper('pim')->__('Vendor ID'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'vendor_id',
			)); 
       }
		
        $fieldset->addField('vendor_name', 'text', array(
            'label'     => Mage::helper('pim')->__('Vendor Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'vendor_name',
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('pim')->__('Email'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'email',
        ));
        
        $fieldset->addField('salesperson', 'text', array(
            'label'     => Mage::helper('pim')->__('Sales Person'),
            'name'      => 'salesperson',
        ));
        
        $fieldset->addField('searchterm', 'text', array(
            'label'     => Mage::helper('pim')->__('Search Term'),
            'name'      => 'searchterm',
        ));
        
        $fieldset->addField('street', 'text', array(
            'label'     => Mage::helper('pim')->__('Street'),
            'name'      => 'street',
        ));
        
        $fieldset->addField('city', 'text', array(
            'label'     => Mage::helper('pim')->__('City'),
            'name'      => 'city',
        ));
        
        $fieldset->addField('zip', 'text', array(
            'label'     => Mage::helper('pim')->__('Zip Code'),
            'name'      => 'zip',
        ));
        
        $fieldset->addField('region', 'text', array(
            'label'     => Mage::helper('pim')->__('Region'),
            'name'      => 'region',
        ));
        
        $fieldset->addField('country_id', 'select', array(  
		    'label'     => Mage::helper('pim')->__('Country'),
            'name'      => 'country_id', 
		    'values' 	=> Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),  
	    ));
	    
	    $fieldset->addField('telephone', 'text', array(
            'label'     => Mage::helper('pim')->__('Telephone'),
            'name'      => 'telephone',
        ));
	    
	    $fieldset->addField('status', 'select', array(  
		    'label'     => Mage::helper('pim')->__('Status'),
            'name'      => 'status', 
		    'values'	=> array('A' => 'Active', 'I' => 'Inactive'),  
	    ));
	     
	    if($data['id'])
	    {
		    // New password
                $fieldset->addField('new_password', 'text',
                    array(
                        'label' => Mage::helper('pim')->__('New Password'),
                        'name'  => 'new_password',
                        'class' => 'validate-password'
                    )
                );
	    }
	    else
	    {
		   $fieldset->addField('new_password', 'text', array(
	          'label'     => Mage::helper('pim')->__('New Password'),
	          'class' 	  => 'input-text required-entry validate-password',
	          'required'  => true,
	          'name'      => 'new_password',
	        )); 
	    }
	    
		
		$form->setValues($data);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    
}