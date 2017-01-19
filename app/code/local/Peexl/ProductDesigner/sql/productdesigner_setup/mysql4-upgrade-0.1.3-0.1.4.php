<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_design')} ADD COLUMN title text;
ALTER TABLE {$this->getTable('catalog_product_designer_design')} ADD COLUMN type text;
ALTER TABLE {$this->getTable('catalog_product_designer_design')} ADD COLUMN updated INT;
    
");

$installer->endSetup();