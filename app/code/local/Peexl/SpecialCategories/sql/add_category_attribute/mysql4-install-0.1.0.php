<?php
$this->startSetup();
$this->addAttribute('catalog_category', 'is_special_category', array(
    'group'         => 'General',
    'type'          => 'int',
    'input'         => 'select',
    'source'        => 'eav/entity_attribute_source_boolean',
    'label'         => 'Special Category',
    'backend'       => '',
    'default'       => 0,
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => false,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
$this->endSetup();