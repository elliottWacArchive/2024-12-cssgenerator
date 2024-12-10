<?php

$files = $argv;

array_shift($files);

$images = [];

foreach($files as $key => $val) {
    if (!is_dir($val)) {
        $images[$i] = $val;
    } else {
        my_scandir($val, $images);
    }
}

function makeImg($imgs)
{
    $pos_x = 0;
    $pos_y = 0;
    $x = 0;
    $y = 0;
    $i = 0;
    foreach ($imgs as $img) {
        $sizes = getimagesize($img);
        if ($i == 0) {
            $y = $sizes[1];
        } elseif ($sizes[1] > $y) {
            $y = $sizes[1];
        }
        $x = $x + $sizes[0];
        $i++;
    }
    $gdg = imagecreatetruecolor($x, $y);
    foreach ($imgs as $img) {
        $sizes = getimagesize($img);
        imagecopy($gdg, imagecreatefrompng($img), $pos_x, $pos_y, 0, 0, $sizes[0], $sizes[1]);
        $pos_x = $pos_x + $sizes[0];
    }
    imagepng($gdg, "output.png");
}

// $arr = array("1.png","2.png","blue.png");

makeImg($arr);
