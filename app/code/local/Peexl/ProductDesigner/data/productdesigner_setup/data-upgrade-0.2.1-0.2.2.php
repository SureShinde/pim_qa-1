<?php

$fonts = array(
    array(
        'name' => 'Arial',
    ),
    array(
        'name' => 'Tahoma',
    ),
    array(
        'name' => 'Helvetica',
    ),
    array(
        'name' => 'sans-serif',
    ),
    array(
        'name' => 'Verdana',
    ),
    array(
        'name' => 'Times New Roman',
    ),
    array(
        'name' => 'Georgia',
    )
);


foreach ($fonts as $font) {
    Mage::getModel('productdesigner/font')
        ->setData($font)
        ->save();
}