#!/usr/bin/env php
<?php

const OFFSET = 14400000;
const SIZE = 10000;
const REPEAT_TIME = 1000;

function intersect_in_array(array $array1, array $array2) {
    $result = [];
    foreach ($array1 as $val) {
        if (in_array($val, $array2)) {
            $result[] = $val;
        }
    }
    return $result;
}

function intersect_flip_isset(array $array1, array $array2) {
    $result = [];
    $flipped = array_flip($array2);
    foreach ($array1 as $val) {
        if (isset($flipped[$val])) {
            $result[] = $val;
        }
    }
    return $result;
}

function intersect_flip_array_key_exists(array $array1, array $array2) {
    $result = [];
    $flipped = array_flip($array2);
    foreach ($array1 as $val) {
        if (array_key_exists($val, $flipped)) {
            $result[] = $val;
        }
    }
    return $result;
}

function intersect_standard_func(array $array1, array $array2) {
    return array_intersect($array1, $array2);
}

function speed_test(string $func_name, callable $test_func) {
    $multiples_of_2 = range(OFFSET, OFFSET + SIZE, 2);
    $multiples_of_3 = range(OFFSET, OFFSET + SIZE, 3);

    echo "----- " . $func_name . " -----" . PHP_EOL;
    echo "Start: " . date("Y/m/d H:i:s") . PHP_EOL;
    $start_time = microtime(true);

    for ($i = 0; $i < REPEAT_TIME; $i++) {
        $result = $test_func($multiples_of_2, $multiples_of_3);
    }

    $end_time = microtime(true);
    $diff = $end_time - $start_time;
    echo "End: " . date("Y/m/d H:i:s") . PHP_EOL;
    echo "Elapsed time: " . sprintf("%.4f", $diff) . "s" . PHP_EOL;
    echo "Speed: " . sprintf("%.4f", 1000 * $diff / REPEAT_TIME) . "ms per time" . PHP_EOL;
    $expected_size = intdiv(SIZE, 6) + 1;
    if (count($result) !== $expected_size) {
        echo "ERROR: wrong function" . PHP_EOL;
        echo "    Expected array size: " . (string)$expected_size . PHP_EOL;
        echo "    Actual array size: " . (string)count($result) . PHP_EOL;
    }
}

speed_test('array_intersect', 'intersect_standard_func');
speed_test('flip, isset', 'intersect_flip_isset');
