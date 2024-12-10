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
        }
    }
    return $arr;
}

function makeImg($imgs)
{
    global $output;
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
    imagepng($gdg, $output);
}

function makeCss($imgs)
{
    global $output;
    global $cssoutput;
    $pos_x = 0;
    $pos_y = 0;
    $x = 0;
    $y = 0;
    $i = 1;
    $var= "";
    foreach ($imgs as $img) {
        $size = getimagesize($img);
        $var = $var . ".sprite-$i {background: url('$output') no-repeat; background-position: $pos_x px $pos_y px; width: $size[0] px; height: $size[1] px}\n";
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

if (count($files) < 1) {
    echo "Erreur! : Aucun paramètre passé!\n";
    die();
}

$rec = false;
$output = "output.png";
$cssoutput = "style.css";

foreach ($files as $key => $val) {
    if ($val == "-r" || $val == "--recursive") {
        $rec = true;
        unset($files[$key]);
    } elseif ($val == "-i" || $val == "--output-image") {
        $output = $files[$key + 1];
        unset($files[$key]);
        unset($files[$key + 1]);
    } elseif ($val == "-s" || $val == "--output-style") {
        $cssoutput = $files[$key + 1];
        unset($files[$key]);
        unset($files[$key + 1]);
    }
}
$tmp = list_files($files);
$imgs = imgCheck($tmp);
makeImg($imgs);
makeCss($imgs);
