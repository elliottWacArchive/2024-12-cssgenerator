<?php

function makeHtml($imgs)
{
    global $cssoutput;
    $i = 1;
    $content = '
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SpriteSheet</title>
        <link rel="stylesheet" href="' . $cssoutput . '">
    </head>
    <body>' . "\n";
    foreach ($imgs as $key => $val) {
        $content = $content . '        <div class="sprite-' . $i . '"></div>' . "\n";
        $i++;
    }
    $content = $content . "    </body>\n";
    file_put_contents("index.html", $content);
}
