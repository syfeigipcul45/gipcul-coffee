
<?php

if (!function_exists('formatNumberToK')) {
    function formatNumberToK($number) {
        if ($number >= 1000) {
            return number_format($number / 1000, 0) . 'k';
        }
        return $number;
    }
}
