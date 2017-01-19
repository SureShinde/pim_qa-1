<?php
$this->startSetup();

$this->run("
DROP TABLE IF EXISTS {$this->getTable('pim/skugenerator')};
CREATE TABLE {$this->getTable('pim/skugenerator')} (
`id` int(11) unsigned NOT NULL auto_increment,
`key` varchar(5) NOT NULL,
`running_alpha` varchar(5) NULL DEFAULT '',
`running_number` varchar(5) NOT NULL DEFAULT '00',
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$this->endSetup();