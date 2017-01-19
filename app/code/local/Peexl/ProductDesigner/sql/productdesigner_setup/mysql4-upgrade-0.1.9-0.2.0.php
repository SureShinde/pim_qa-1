<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_color')};
CREATE TABLE {$this->getTable('catalog_product_designer_color')} (
	id                  int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,name	  	          text		NOT NULL 	default ''
	,value              text		NOT NULL 	default ''
  ,position           int(10)	unsigned	NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_font_color')};
CREATE TABLE {$this->getTable('catalog_product_designer_font_color')} (
	id                  int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,name	  	          text		NOT NULL 	default ''
	,value              text		NOT NULL 	default ''
  ,position           int(10)	unsigned	NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
");

$installer->endSetup();