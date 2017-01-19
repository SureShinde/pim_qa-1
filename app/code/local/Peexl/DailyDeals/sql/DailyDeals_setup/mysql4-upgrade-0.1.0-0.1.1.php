<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE  `".$this->getTable('peexl_dailydeals/deals')."` ADD  `stores` TINYTEXT NOT NULL");

$installer->endSetup();