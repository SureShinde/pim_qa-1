<?php

$this->addAttribute( Mage_Catalog_Model_Product::ENTITY, 'show_preview', array(
            'group' => 'General',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Show preview?',
            'input' => 'boolean',
            'source' => 'eav/entity_attribute_source_boolean',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'default' => '',
            'unique' => false,
            'apply_to' => 'package',
        ) );
