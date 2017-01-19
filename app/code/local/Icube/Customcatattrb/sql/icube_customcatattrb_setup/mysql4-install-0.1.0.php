<?php
$installer = $this;
$installer->startSetup();

$attribute  = array(	
    'group'             => 'General Information',
    'type'              => 'varchar',
    'label'             => 'Category Code',
    'input'             => 'text',
    'required'          => false,
    'sort_order'        => 10,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'user_defined'      => true,
    'input_renderer'	=> 'pim/category_attribute_disable_text',
    
);
$installer->addAttribute('catalog_category', 'category_code', $attribute);

$installer->endSetup();
