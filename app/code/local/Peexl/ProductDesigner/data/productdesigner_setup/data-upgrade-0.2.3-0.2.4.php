<?php

$patterns = array(
    array(
        'image' => 'productdesigner/gallery/patterns/1.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/2.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/3.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/4.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/5.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/6.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/7.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/8.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/9.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/10.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/11.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/12.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/13.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/14.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/15.png',
    ),
    array(
        'image' => 'productdesigner/gallery/patterns/16.png',
    ),
);


foreach ($patterns as $pattern) {
    Mage::getModel('productdesigner/pattern')
        ->setData($pattern)
        ->save();
}