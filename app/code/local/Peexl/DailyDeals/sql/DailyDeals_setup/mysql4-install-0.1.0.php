<?php

$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('peexl_dailydeals')};
CREATE TABLE {$this->getTable('peexl_dailydeals')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `deal_price` float(11,2) NOT NULL,
  `deal_qty` int(11) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `deal_status` int(11) NOT NULL,
  `deal_position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;   
");


$installer->endSetup();