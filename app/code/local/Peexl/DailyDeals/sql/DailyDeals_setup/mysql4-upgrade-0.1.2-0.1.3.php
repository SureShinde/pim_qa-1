<?php

$installer = $this;
$installer->startSetup();

$installer->run("DROP TABLE IF EXISTS {$this->getTable('peexl_dailydeals/sales')};
CREATE TABLE {$this->getTable('peexl_dailydeals/sales')}(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `dailydeal_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; ");

$installer->endSetup();