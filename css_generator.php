<?php

function list_files($files)
{
    $images = [];
    foreach ($files as $key => $val) {
        if (!is_dir($val)) {
            array_push($images, $val);
        } else {
            my_scandir($val, $images);
        }
    }
    return $images;
}

function imgCheck($arr)
{
    foreach ($arr as $key => $val) {
        $info = new SplFileInfo($val);
        if ($info->getExtension() != "png") {
            echo "Erreur! : Fichier $val incompatible!\n";
            unset($arr[$key]);
        } elseif (!file_exists($val)) {
            echo "Erreur! : Fichier $val n'existe pas!\n";
            unset($arr[$key]);
        }
    }
    return $arr;
}

function makeImg($imgs)
{
    global $output;
    global $osize;
    $pos_x = 0;
    $pos_y = 0;
    $x = 0;
    $y = 0;
    $i = 0;
    foreach ($imgs as $img) {
        if ($osize != []) {
            $y = $osize[1];
            $x = $x + $osize[0];
        } else {
            $sizes = getimagesize($img);
            if ($i == 0) {
                $y = $sizes[1];
            } elseif ($sizes[1] > $y) {
                $y = $sizes[1];
            }
            $x = $x + $sizes[0];
        }
        $i++;
    }
    $gdg = imagecreatetruecolor($x, $y);
    foreach ($imgs as $img) {
        $sizes = getimagesize($img);
        if ($osize != []) {
            $gd = imagecreatetruecolor($osize[0], $osize[1]);
            imagecopyresampled($gd, imagecreatefrompng($img), 0, 0, 0, 0, $osize[0], $osize[1], $sizes[0], $sizes[1]);
            imagecopy($gdg, $gd, $pos_x, $pos_y, 0, 0, $osize[0], $osize[1]);
            $pos_x = $pos_x + $osize[0];
        } else {
            imagecopy($gdg, imagecreatefrompng($img), $pos_x, $pos_y, 0, 0, $sizes[0], $sizes[1]);
            $pos_x = $pos_x + $sizes[0];
        }
    }
    imagepng($gdg, $output);
}

function makeCss($imgs)
{
    global $osize;
    global $output;
    global $cssoutput;
    $pos_x = 0;
    $pos_y = 0;
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
    width: $size[0]" . "px; height: $size[1]" . "px
    }\n";
        $pos_x = $pos_x + $size[0];
        $i++;
    }
    file_put_contents($cssoutput, $var);
}

function my_scandir($val, &$tab)
{
    global $rec;
    if ($handle = opendir($val)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($val . "/" . $entry)) {
                    if ($rec == true) {
                        my_scandir($val . "/" . $entry, $tab);
                    }
                } else {
                    array_push($tab, $val . "/" . $entry);
                }
            }
        }
    }
    closedir($handle);
}

$files = $argv;

array_shift($files);

$rec = false;
$output = "output.png";
$cssoutput = "style.css";
$osize = [];

foreach ($files as $key => $val) {
    switch($val) {
        case "-r":
            $rec = true;
            unset($files[$key]);
            break;
        case "-i":
            $output = $files[$key + 1];
            unset($files[$key]);
            unset($files[$key + 1]);
            break;
        case "-s":
            $cssoutput = $files[$key + 1];
            unset($files[$key]);
            unset($files[$key + 1]);
            break;
        case "-o":
            $osize = array($files[$key + 1], $files[$key + 1]);
            unset($files[$key]);
            unset($files[$key + 1]);
            break;
    }
}

$tmp = list_files($files);
$imgs = imgCheck($tmp);

if ($imgs == []) {
    echo "Erreur! : Aucun paramètre passé/valide!\n";
    die();
}

makeImg($imgs);
makeCss($imgs);
