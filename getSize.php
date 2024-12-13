<?php

function getSize($imgs)
{
    global $osize;
    global $padding;
    $x = 0;
    $y = 0;
    $i = 0;
    foreach ($imgs as $img) {
        if ($osize != []) { // BOBOBOBOBOBONNNNUS
            $y = $osize[1] + 2 * $padding;
            $x = $x + $osize[0] + 2 * $padding;
        } else {
            $sizes = getimagesize($img);
            if ($i == 0) {
                $y = $sizes[1] + 2 * $padding;
            } elseif ($sizes[1] > $y) {
                $y = $sizes[1] + (2 * $padding);
            }
            $x = $x + $sizes[0] + (2 * $padding);
        }
        $i++;
    }
    return [$x, $y, $padding, $i];
}
