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
        array('status' => 'confirmed', 'label' => 'Confirmed'),
        array('status' => 'cannot_fulfill', 'label' => 'Cannot Fulfill'),
    )
);

$this->endSetup();