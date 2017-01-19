<?php

$installer = $this;
 
$installer->startSetup();
 
$installer->run("
UPDATE eav_attribute SET attribute_code='weight_wo_chain' WHERE attribute_code = 'weight _wo_chain';
UPDATE eav_attribute SET attribute_code='test_current_or_freq' WHERE attribute_code = 'test_current /freq';
UPDATE eav_attribute SET attribute_code='drawer_or_shelve' WHERE attribute_code = 'drawer / shelve';
    ");
 
$installer->endSetup();