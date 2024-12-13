<?php

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
