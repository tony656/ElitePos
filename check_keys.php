<?php
$file = file_get_contents('resources/views/newOrder.blade.php');
$langFile = file_get_contents('resources/lang/en/messages.php');
// Match both simple @lang('messages.KEY') and parameterized @lang('messages.KEY', [...])
preg_match_all("/@lang\('messages\.([^']+)'\)/", $file, $bladeKeys);
preg_match_all("/@json\(__\('messages\.([^']+)'\)\)/", $file, $bladeKeys2);
$allKeys = array_merge($bladeKeys[1], $bladeKeys2[1]);
preg_match_all("/'([^']+)' => /", $langFile, $langKeys);
$bladeSet = array_unique($allKeys);
$langSet = array_unique($langKeys[1]);
echo "Blade keys missing from en lang file:\n";
foreach ($bladeSet as $k) {
    if (!in_array($k, $langSet)) {
        echo "  - $k\n";
    }
}
