<?php

$fonts = array(
    array(
        'name' => 'Blokletters-Balpen',
        'file' => 'Blokletters-Balpen.ttf'
    ),
    array(
        'name' => 'Hawaii',
        'file' => 'Hawaii_Killer.ttf'
    ),
    array(
        'name' => 'Ubuntu',
        'file' => 'Ubuntu-R.ttf'
    ),
);


foreach ($fonts as $font) {
    Mage::getModel('productdesigner/font')
        ->setData($font)
        ->save();
}