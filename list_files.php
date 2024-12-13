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
