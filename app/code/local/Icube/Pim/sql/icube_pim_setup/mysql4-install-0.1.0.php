<?php
$this->startSetup();

$this->run("
DROP TABLE IF EXISTS {$this->getTable('pim/vendor')};
CREATE TABLE {$this->getTable('pim/vendor')} (
`vendor_id` int(11) unsigned NOT NULL auto_increment,
`vendor_name` varchar(255) NOT NULL,
`email` varchar(127) NOT NULL,
`street` varchar(100) NOT NULL,
`city` varchar(50) NOT NULL,
`zip` varchar(20) default NULL,
`country_id` char(2) NOT NULL,
`region` varchar(50) default NULL,
`status` char(1) NOT NULL,
`password` varchar(50) default NULL,
PRIMARY KEY  (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$this->endSetup();