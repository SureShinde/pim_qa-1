<?php
$this->startSetup();

$this->run("
ALTER TABLE sales_flat_shipment ADD vendor_id INT(11);
");

$this->endSetup();