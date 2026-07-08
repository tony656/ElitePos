<?php
$swFile = file_get_contents('resources/lang/sw/messages.php');
preg_match_all("/@lang\('messages\.([^']+)'\)/", file_get_contents('resources/views/newOrder.blade.php'), $bladeKeys);
preg_match_all("/'([^']+)' => /", $swFile, $langKeys);
$bladeSet = array_unique($bladeKeys[1]);
$langSet = array_unique($langKeys[1]);
echo "Blade keys missing from sw lang file:\n";
foreach ($bladeSet as $k) {
    if (!in_array($k, $langSet)) {
        echo "  - $k\n";
    }
}
