<?php
$this->startSetup();

$this->run("
ALTER TABLE {$this->getTable('pim/vendor')} 
DROP PRIMARY KEY,
ADD id int(11) unsigned NOT NULL AUTO_INCREMENT FIRST,
ADD searchterm varchar(20) NULL,
ADD salesperson varchar(25) NULL,
ADD telephone varchar(25) NULL,
ADD PRIMARY KEY (id),
MODIFY COLUMN vendor_id varchar(50) NOT NULL;
");

$this->run("
INSERT INTO `icube_pim_vendor` (`vendor_id`, `vendor_name`, `email`, `street`, `city`, `zip`, `country_id`, `region`, `status`, `password`, `searchterm`, `salesperson`, `telephone`)
VALUES
	('1001002800', 'PT. FORTA LARESE', 'vendor@klikmro.com', 'JL. MUSI NO. 16 KEL. CIDENG', 'JAKARTA PUSAT', '10150', 'ID', NULL, 'A', '482c811da5d5b4bc6d497ffa98491e38', 'FORTA', 'NANCY / HANNY', '3861018'),
	('1001002803', 'PT. HENGTRACO PROTECSINDO', 'vendor@klikmro.com', 'JL. PANGERAN JAYAKARTA NO. 93 BC', 'JAKARTA PUSAT', '10730', 'ID', NULL, 'A', '482c811da5d5b4bc6d497ffa98491e38', 'HENGTRACO', 'FELICIA', '81214212127'),
	('1001002805', 'PT RIRANA PRATAMA MANDIRI', 'vendor@klikmro.com', 'TAMAN HARAPAN BARU BLOK A-2', 'BEKASI', '17131', 'ID', NULL, 'A', '482c811da5d5b4bc6d497ffa98491e38', 'RIRANA', 'RYAN', '81286277775');
");

$this->endSetup();