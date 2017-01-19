<?php
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('catalog/product_option_type_value'), 'description', 'TEXT NULL');
$installer->getConnection()->addColumn($installer->getTable('catalog/product_option_type_value'), 'image', 'TEXT NULL');


$installer->endSetup();
?>
