<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('catalog_product_designer_design')} ADD COLUMN value text;
    
");

$installer->endSetup();