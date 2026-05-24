<?php

// Test formatted sold logic
function testFormat($sold) {
    if ($sold >= 10000) {
        $value = $sold / 1000;
        $formatted = number_format($value, 1, ',', '');
        if (str_ends_with($formatted, ',0')) {
            $formatted = substr($formatted, 0, -2);
        }
        return $formatted . 'rb';
    }
    return number_format($sold, 0, ',', '.');
}

$testCases = [
    0 => '0',
    5 => '5',
    999 => '999',
    1000 => '1.000',
    9500 => '9.500',
    9999 => '9.999',
    10000 => '10rb',
    10100 => '10,1rb',
    13700 => '13,7rb',
    13777 => '13,8rb',
    100000 => '100rb',
];

foreach ($testCases as $input => $expected) {
    $result = testFormat($input);
    if ($result === $expected) {
        echo "PASS: $input -> $result\n";
    } else {
        echo "FAIL: $input -> $result (Expected: $expected)\n";
    }
}
