<?php
function loadLanguage($lang = 'es') {
    $file = __DIR__ . "/$lang.txt";

    if (!file_exists($file)) {
        $file = __DIR__ . "/es.txt"; 
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $translations = [];

    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $translations[trim($key)] = trim($value);
        }
    }

    return $translations;
}
?>
