<?php

$images = array(
    array('name'=>'Lobster','image'=>'productdesigner/gallery/images/lobster.png'),
    array('name'=>'Shark 2','image'=>'productdesigner/gallery/images/shark2.png'),
    array('name'=>'Shark','image'=>'productdesigner/gallery/images/shark.png'),
    array('name'=>'Parot ','image'=>'productdesigner/gallery/images/parot.png'),
    array('name'=>'Parot 2','image'=>'productdesigner/gallery/images/parot2.png'),
    array('name'=>'Panther','image'=>'productdesigner/gallery/images/panther.png'),
    array('name'=>'Panthers','image'=>'productdesigner/gallery/images/panthers.png'),
    array('name'=>'Lion','image'=>'productdesigner/gallery/images/lion-vector.png'),
    array('name'=>'Eagle','image'=>'productdesigner/gallery/images/eagle.png'),
    array('name'=>'Eagle 2','image'=>'productdesigner/gallery/images/eagle2.png'),
    array('name'=>'Eagle 3','image'=>'productdesigner/gallery/images/eagle3.png'),
    array('name'=>'Butterfly','image'=>'productdesigner/gallery/images/butterfly.png')
);


foreach ($images as $image) {
    Mage::getModel('productdesigner/graphics')
        ->setData($image)
        ->save();
}