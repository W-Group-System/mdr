<?php

function getOrdinal($number) {
    $number = (int) $number;

    if (in_array(($number % 100), [11, 12, 13])) {
        return $number . 'th';
    }

    switch ($number % 10) {
        case 1:
            return $number . 'st of the Month';
        case 2:
            return $number . 'nd of the Month';
        case 3:
            return $number . 'rd of the Month';
        default:
            return $number . 'th of the Month';
    }
}

