<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_design')} 
    ADD `from_admin` TINYINT( 1 ) NOT NULL DEFAULT  '0',
    ADD INDEX (  `from_admin` );
    
");

$installer->endSetup();