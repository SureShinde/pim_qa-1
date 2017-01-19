<?php
$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
$installer->startSetup();
$dbname = (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');
$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
$sql = "SELECT concat( 'ALTER TABLE ', TABLE_NAME, ' MODIFY COLUMN ', COLUMN_NAME, ' decimal(17,4)' ) AS TABLE_NAME 
from information_schema.columns
where table_schema = '{$dbname}' 
and column_type = 'decimal(12,4)';";
$rows = $connection->fetchAll($sql);
foreach ($rows as $row) {
	$installer->run($row['TABLE_NAME']);
}
$installer->endSetup();
?>