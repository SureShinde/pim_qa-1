<?php

$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('customer_bonus')};
CREATE TABLE {$this->getTable('customer_bonus')} (
        id                 int(10)	unsigned	NOT NULL	auto_increment	PRIMARY KEY
	,customer_id       int(10) NOT NULL
        ,referal_id        int(10)  NOT NULL
        ,action            varchar(255)   NOT NULL
	,bonus             int(10) NOT NULL
        ,order_id          int(10) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;    
");

$installer->addAttribute('customer', 'px_uid', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Unique ID',
    'global' => 1,
    'visible' => 1,
    'required' => 0,
    'user_defined' => 0,
    'default' => '',
    'visible_on_front' => 0,
    'source' =>   NULL,
));
 
$installer->addAttribute('customer', 'px_referal_id', array(
    'type' => 'int',
    'input' => 'text',
    'label' => 'Referal ID',
    'visible' => 1,
    'required' => 0,
    'user_defined' => 0,
    'default' => '',
    'visible_on_front' => 0,
    'source' =>   NULL,
));
 

 
$installer->endSetup();