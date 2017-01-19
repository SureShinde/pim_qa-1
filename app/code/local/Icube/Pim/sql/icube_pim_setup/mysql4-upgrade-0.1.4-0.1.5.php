<?php
$this->startSetup();

$statusTable = $this->getTable('sales/order_status');

$this->getConnection()->insertArray(
    $statusTable,
    array(
        'status',
        'label'
    ),
    array(
        array('status' => 'shipped', 'label' => 'Shipped'),
    )
);

$this->endSetup();