<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('catalog_product_designer_font')};
CREATE TABLE {$this->getTable('catalog_product_designer_font')} (
	id                  int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,name	  	          text		NOT NULL 	default ''
	,font_family        text		NOT NULL 	default ''
	,file			          text		NOT NULL 	default ''
  ,position           int(10)	unsigned	NOT NULL
  ,enabled            int(1)	unsigned	NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
");

$installer->endSetup();