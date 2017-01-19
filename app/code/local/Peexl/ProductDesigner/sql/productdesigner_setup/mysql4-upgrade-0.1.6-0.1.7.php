<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_design')} 
    ADD `product_id` INT,
    ADD INDEX (  `product_id` );
    
");

$installer->endSetup();