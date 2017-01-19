<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_design')} ADD COLUMN customer_id INT NOT NULL DEFAULT  '0',
ADD INDEX (  `customer_id` );
    
");

$installer->endSetup();