<?php

$installer = $this;

$installer->startSetup();
$table = $installer->getTable('eav_attribute');
$installer->getConnection()->addColumn(
        $table, 'available_for_vendor', array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'nullable' => false, 
            'comment' => 'Available For Vendor',
            'default' => 0
        ));
        
$installer->endSetup();