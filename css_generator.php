<?php

require("list_files.php");
require("getSize.php");
require("imgCheck.php");
require("makeCss.php");
require("makeImg.php");
require("my_scandir.php");
require("makeHtml.php");

$files = $argv;

array_shift($files);

$rec = false;
$output = "output.png";
$cssoutput = "style.css";
$osize = [];
$padding = 0;
$hmtl = false;

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
    } elseif ($val == "-w" || $val == "--html-file") {
        $html = true;
        unset($files[$key]);
    } elseif ($val == "-h" || $val == "--help") {
        echo "\nJe vois que tu as besoin d'aide! (｡˃ ᵕ ˂ )⸝♡\n\n";
        echo "Execution : css_generator.php [PARAMS] [<path>]\n";
        echo "Params : \n";
        echo "  [-h|--help] : envoie la liste des commande.\n";
        echo "  [-r|--recursive] : parcours des fichiers de manière récursive.\n";
        echo "  [-w|--html-file] : renvoie un fichier css avec toutes les div pour le css.\n";
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

if (isset($html)) {
    makeHtml($imgs);
}
