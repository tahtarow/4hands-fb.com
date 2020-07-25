<?php

include 'lessc.inc.php';

function less($file, $cash = true)
{
    $less = new lessc;

    if ($cash) {
        $file_css = str_replace('.less', '.css', $file);
        $less->checkedCompile($file, $file_css);
        $file_css = explode($_SERVER['SERVER_NAME'], $file_css)[1];
        echo '<link href="' . $file_css . '" rel="stylesheet" type="text/css">' . "\n";
    } else {
        echo '<style>' . ($less->compileFile($file)) . '</style>';

    }

}

