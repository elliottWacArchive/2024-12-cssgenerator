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
$padding = 0;

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
    } elseif ($val == "-o" || $val == "--override-size") {
        $osize = array($files[$key + 1], $files[$key + 1]);
        unset($files[$key]);
        unset($files[$key + 1]);
    } elseif ($val == "-p" || $val == "--padding") {
        $padding = $files[$key + 1];
        unset($files[$key]);
        unset($files[$key + 1]);
    } elseif ($val == "-h" || $val == "--help") {
        echo "\nJe vois que tu as besoin d'aide! (｡˃ ᵕ ˂ )⸝♡\n\n";
        echo "Execution : css_generator.php [PARAMS] [<path>]\n";
        echo "Params : \n";
        echo "  [-h|--help] : envoie la liste des commande.\n";
        echo "  [-r|--recursive] : parcours des fichiers de manière récursive.\n";
        echo "  [-s|--output-style] [<file>] : renvoie le fichier css avec le nom passé en paramètre.\n";
        echo "  [-i|--output-image] [<file>] : renvoie le fichier image avec le nom passé en paramètre.\n";
        echo "  [-o|--override-size] [<size>] : force la taille des images.\n";
        echo "  [-p|--padding] [<size>] : ajoute du padding aux images.\n";
        echo "Path : \n";
        echo "  [<path> <path> ...] : ajoute toutes les images png dont les chemins sont passés en paramètre.\n";
        echo "  [<path_dir> <path_dir> ...] : ";
        echo "ajoute toutes les images png des dossiers dont les chemins sont passés en paramètre.\n";
        die();
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
