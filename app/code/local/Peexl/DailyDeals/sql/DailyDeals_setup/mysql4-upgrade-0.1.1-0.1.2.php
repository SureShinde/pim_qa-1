<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE  `".$this->getTable('peexl_dailydeals/deals')."` ADD  `deal_views` INT NOT NULL DEFAULT  '0'");

$installer->endSetup();