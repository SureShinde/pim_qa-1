<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('catalog_product_package_item_option')} 
  ADD preview_image varchar(255);
");

$installer->endSetup();
