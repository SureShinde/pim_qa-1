<?php

$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('customer_bonus_discounts')};
CREATE TABLE {$this->getTable('customer_bonus_discounts')} (
        id                 int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,total_from        int(10) NOT NULL
        ,total_to          int(10)  NOT NULL        
	,discount_type     int(2) NOT NULL        
        ,discount_value    float(11,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();