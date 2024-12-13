<?php

function makeCss($imgs)
{
    global $osize;
    global $output;
    global $cssoutput;
    global $padding;
    $pos_x = $padding;
    $pos_y = $padding;
    $x = 0;
    $y = 0;
    $i = 1;
    $var = "";
    foreach ($imgs as $img) {
        if ($osize != []) {
            $size = $osize;
        } else {
            $size = getimagesize($img);
        }
        $var = $var . ".sprite-$i {
    display: inline-block;
    background: url('$output') no-repeat;
    background-position: -$pos_x" . "px -$pos_y" . "px;
    width: $size[0]" . "px; height: $size[1]" . "px;
    margin: $padding" . "px;
    }\n";
        $pos_x = $pos_x + $size[0] + 2 * $padding;
        $i++;
    }
    file_put_contents($cssoutput, $var);
}
