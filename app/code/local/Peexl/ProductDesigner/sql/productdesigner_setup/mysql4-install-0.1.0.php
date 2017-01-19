<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_option')};
CREATE TABLE {$this->getTable('catalog_product_designer_option')} (
	option_id                       int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,option_code			varchar(255)		NOT NULL 	default ''
	,option_title			varchar(255)		NOT NULL 	default ''
	,name_label			varchar(255)		NOT NULL 	default ''
        ,value_label			varchar(255)		NOT NULL 	default ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_option_value')};
CREATE TABLE {$this->getTable('catalog_product_designer_option_value')} (
	value_id		int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,option_id              int(10)	unsigned	NOT NULL
	,product_id		int(10) unsigned	NOT NULL
        ,value			text                    NOT NULL 	default ''
	,CONSTRAINT FK_CATALOG_PRODUCT_DESIGNER_OPTION_VALUE_OPTION_ID FOREIGN KEY (option_id)
		REFERENCES {$this->getTable('catalog_product_designer_option')} (option_id)
		ON DELETE CASCADE ON UPDATE CASCADE
	,CONSTRAINT FK_CATALOG_PRODUCT_DESIGNER_OPTION_VALUE_PRODUCT_ENTITY FOREIGN KEY (product_id)
		REFERENCES {$this->getTable('catalog_product_entity')} (entity_id)
		ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_design')};
CREATE TABLE {$this->getTable('catalog_product_designer_design')} (
        id                                 int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,design_id                         varchar(13)             
	,info                              text                    NOT NULL 	default ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_order_item_design')};
CREATE TABLE {$this->getTable('catalog_product_designer_order_item_design')} (
	id                      int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,order_id               int(10)	unsigned	NOT NULL
	,item_id		int(10) unsigned	NOT NULL
        ,design_id		varchar(13)             NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO {$this->getTable('catalog_product_designer_option')} (`option_code`, `option_title`, `name_label`, `value_label`) VALUES 
('colors_color', '<h3>Color</h3> hexValue - comma-separated colors hex codes for colorizing areas', 'Color Name', 'hexValue'),
('sizes_size', '<h3>Size</h3>', 'Size Name', 'Size Value'),
('printarea', '<h3>Print Area</h3> Image URL - path to the image inside folder \{\{designer_path\}\}', 'Name', 'Image URL');

");

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'designer_enabled', array(
    'group' => 'Designer Attributes',
    'type' => 'int',
    'backend' => '',
    'frontend' => '',
    'label' => 'Designer Enabled?',
    'input' => 'boolean',
    'source' => 'eav/entity_attribute_source_table',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible' => 0,
    'required' => 0,
    'user_defined' => 0,
    'default' => '',
    'searchable' => 0,
    'filterable' => 0,
    'comparable' => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search' => 0,
    'used_in_product_listing' => 1,
    'unique' => 0,
));

$installer->addAttribute('catalog_product', 'designer_product_thumburl', array(
    'group' => 'Designer Attributes',
    'input' => 'text',
    'type' => 'text',
    'label' => 'Thumb URL',
    'backend' => '',
    'visible' => 0,
    'required' => 0,
    'user_defined' => 0,
    'searchable' => 0,
    'filterable' => 0,
    'comparable' => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));


$installer->endSetup();