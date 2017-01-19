<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE  `".$this->getTable('peexl_referfriends/bonus')."` ADD  `logdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Action log date';");

$installer->endSetup();