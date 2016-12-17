<?php

function getPHPFiles($path, $debug = false) {
    $files = [];

    $dir = scandir($path);
    foreach ($dir as $file) {
        if ($file == '.' || $file == '..') continue;
        $filePath = $path . DIRECTORY_SEPARATOR . $file;

        if (is_dir($filePath)) {
            if ($debug) {
                echo "DIR : $filePath\n";
            }

            $count = count($files);
            array_splice($files, $count, 0, getPHPFiles($filePath));
        }
        else if (is_file($filePath)) {
            $pos = strrpos($file, '.php') + 4;
            $len = strlen($file);

            if ($pos == $len) {
                array_push($files, $filePath);

                if ($debug) {
                    echo "PHP : $filePath\n";
                }
            }
            else if ($debug) {
                echo "FILE: $filePath ($pos/$len)\n";
            }
        }
        else if ($debug) {
            echo "ETC : $filePath\n";
        }
    }

    return $files;
}

$files = getPHPFiles('../src/flask-php');
usort($files, function ($a, $b) {
    $ca = substr_count($a, DIRECTORY_SEPARATOR);
    $cb = substr_count($b, DIRECTORY_SEPARATOR);

    if ($ca > $cb) {
        return 1;
    }

    if ($ca < $cb) {
        return -1;
    }

    return 0;
});

foreach ($files as $file) {
    require_once $file;
}