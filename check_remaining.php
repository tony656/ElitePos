<?php
$file = file_get_contents('resources/views/newOrder.blade.php');
// Find lines with English text that are NOT inside @lang(), @json(), {{ }}, or {!! !!}
$lines = explode("\n", $file);
foreach ($lines as $i => $line) {
    // Skip if inside PHP/Blade tags or script/style
    if (strpos($line, '@lang(') !== false || strpos($line, '@json(') !== false) continue;
    if (strpos($line, '{{') !== false && strpos($line, '}}') !== false) continue;
    if (strpos($line, '@include') !== false) continue;
    if (strpos($line, '@if') !== false) continue;
    if (strpos($line, '@foreach') !== false) continue;
    if (strpos($line, '@php') !== false) continue;
    if (strpos($line, '@endif') !== false) continue;
    if (strpos($line, '@endforeach') !== false) continue;
    if (strpos($line, '@endphp') !== false) continue;
    if (strpos($line, '<script') !== false) continue;
    if (strpos($line, '<style') !== false) continue;
    if (strpos($line, '//') !== false) continue;
    // Look for English words in HTML attributes or text content
    if (preg_match('/[a-zA-Z]{3,}/', $line)) {
        echo sprintf("%4d: %s\n", $i+1, trim($line));
    }
}
