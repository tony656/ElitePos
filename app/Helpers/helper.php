<?php

if (!function_exists('formatNumber')) {
    function formatNumber($num) {
        if ($num >= 1000000) {
            return number_format($num / 1000000, 1) . 'm';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1) . 'k';
        }
        return $num;
    }
}
