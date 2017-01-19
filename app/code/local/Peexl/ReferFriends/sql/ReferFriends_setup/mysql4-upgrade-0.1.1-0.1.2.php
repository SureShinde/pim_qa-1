<?php

$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('customer_bonus_rules')};
CREATE TABLE {$this->getTable('customer_bonus_rules')} (
        id                 int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,total_from        float(11,2) NOT NULL
        ,total_to          float(11,2)  NOT NULL        
	,bonus             int(10) NOT NULL        
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();