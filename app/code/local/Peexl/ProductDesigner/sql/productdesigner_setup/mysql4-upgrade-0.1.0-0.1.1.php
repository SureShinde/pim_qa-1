<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_option_value')} ADD COLUMN price DECIMAL(12,4);
    
");

$installer->endSetup();