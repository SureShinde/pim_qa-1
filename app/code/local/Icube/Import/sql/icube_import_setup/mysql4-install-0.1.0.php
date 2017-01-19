<?php

$installer = $this;
 
$installer->startSetup();
 
$installer->run("
 
-- DROP TABLE IF EXISTS {$this->getTable('import/mapping')};
CREATE TABLE {$this->getTable('import/mapping')} (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_set` varchar(255) NOT NULL DEFAULT '',
  `ah_code` varchar(50) NOT NULL DEFAULT '',
  `default_category` varchar(255) DEFAULT NULL,
  `website` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 
-- DROP TABLE IF EXISTS {$this->getTable('import/renamed')};
CREATE TABLE {$this->getTable('import/renamed')} ( 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_set_id` smallint(5) DEFAULT NULL,
  `sku` varchar(64) DEFAULT NULL,
  `attribute_set_id_new` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 
    ");
 
$installer->endSetup();

