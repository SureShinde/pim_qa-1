<?php

$colors = array(
    array(
        'name' => 'Black',
        'value' => '#000000',
    ),
    array(
        'name' => 'White',
        'value' => '#FFFFFF',
    ),
    array(
        'name' => 'Grey',
        'value' => '#9D9E9E',
    ),
    array(
        'name' => 'Magenta',
        'value' => '#EA0C94',
    ),
    array(
        'name' => 'Red',
        'value' => '#D10429',
    ),
    array(
        'name' => 'Claret',
        'value' => '#6B1740',
    ),
    array(
        'name' => 'Light Blue',
        'value' => '#8EB5E3',
    ),
    array(
        'name' => 'Sky Blue',
        'value' => '#0BB5DC',
    ),
    array(
        'name' => 'Royal Blue',
        'value' => '#0632AD',
    ),
    array(
        'name' => 'Dark Blue',
        'value' => '#012369',
    ),
    array(
        'name' => 'Violet',
        'value' => '#6800A2',
    ),
    array(
        'name' => 'Yellow',
        'value' => '#FBE916',
    ),
    array(
        'name' => 'Golden Yellow',
        'value' => '#F7A30F',
    ),
    array(
        'name' => 'Gold',
        'value' => '#C78F39',
    ),
    array(
        'name' => 'Orange',
        'value' => '#F76C00',
    ),
    array(
        'name' => 'Light Green',
        'value' => '#C7DB09',
    ),
    array(
        'name' => 'Green',
        'value' => '#259821',
    ),
    array(
        'name' => 'Dark Green',
        'value' => '#11462B',
    ),
    array(
        'name' => 'Brown',
        'value' => '#755605',
    ),
    array(
        'name' => 'Slate Grey',
        'value' => '#382D28',
    ),
    array(
        'name' => 'Steel Blue',
        'value' => '#4682B4',
    ),
    array(
        'name' => 'Firebrick',
        'value' => '#B22222',
    ),
    array(
        'name' => 'Maroon',
        'value' => '#B03060',
    ),
    array(
        'name' => 'Navajo White 4',
        'value' => '#8B795E',
    ),
    array(
        'name' => 'Dark Olive Green 4',
        'value' => '#6E8B3D',
    ),
    array(
        'name' => 'Indian Red 2',
        'value' => '#EE6363',
    ),
);


foreach ($colors as $color) {
    Mage::getModel('productdesigner/font_color')
        ->setData($color)
        ->save();
}

$colorsCollection = Mage::getModel('productdesigner/font_color')->getCollection()->setOrder('position', 'ASC');
$colorsArray['colors'] = array();
foreach ($colorsCollection as $color) {
    $colorsArray['colors'][] = array('name' => $color->getName(), 'value' => $color->getValue());
}