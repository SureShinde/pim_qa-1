<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('catalog_product_package_item')} 
  ADD description text AFTER title;
");

$installer->endSetup();
