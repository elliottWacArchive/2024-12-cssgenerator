<?php

function imgCheck($arr)
{
    foreach ($arr as $key => $val) {
        $info = new SplFileInfo($val);
        if ($info->getExtension() != "png") {
            echo "Attention! : Fichier $val incompatible!\n";
            unset($arr[$key]);
        } elseif (!file_exists($val)) {
            echo "Attention! : Fichier $val n'existe pas!\n";
            unset($arr[$key]);
        }
    }
    return $arr;
}
