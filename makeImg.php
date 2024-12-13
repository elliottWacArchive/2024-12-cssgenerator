<?php

function makeImg($imgs)
{
    global $osize;
    global $padding;
    global $output;
    $pos_x = 0;
    $pos_y = 0;
    $psize = getSize($imgs);
    $gdg = imagecreatetruecolor($psize[0], $psize[1]);
    foreach ($imgs as $img) {
        $sizes = getimagesize($img);
        $png = imagecreatefrompng($img);
        if ($osize != []) {
            $gd = imagecreatetruecolor($osize[0] + (2 * $psize[2]), $osize[1] + (2 * $psize[2]));
            imagecopyresampled($gd, $png, $psize[2], $psize[2], 0, 0, $osize[0], $osize[1], $sizes[0], $sizes[1]);

            imagecopy($gdg, $gd, $pos_x, $pos_y, 0, 0, $osize[0] + (2 * $psize[2]), $osize[1] + (2 * $psize[2]));
            $pos_x = $pos_x + $osize[0] + (2 * $psize[2]);
        } else {
            $gd = imagecreatetruecolor($sizes[0] + (2 * $psize[2]), $sizes[1] + (2 * $psize[2]));
            imagecopy($gd, $png, $psize[2], $psize[2], 0, 0, $sizes[0], $sizes[1]);
            imagecopy($gdg, $gd, $pos_x, $pos_y, 0, 0, $sizes[0] + (2 * $psize[2]), $sizes[1] + (2 * $psize[2]));
            $pos_x = $pos_x + $sizes[0] + (2 * $psize[2]);
        }
    }
    imagepng($gdg, $output);
}
