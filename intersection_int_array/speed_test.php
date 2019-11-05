#!/usr/bin/env php
<?php

const OFFSET = 0;
const SIZES = [10 ** 1, 10 ** 2, 10 ** 3, 10 ** 4, 10 ** 5, 10 ** 6, 10 ** 7];
const REPEAT_TIME = 10000;

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

function intersect_flip_isset_cached(array $array1, array $array2) {
    $result = [];
    foreach ($array1 as $val) {
        if (isset($array2[$val])) {
            $result[] = $val;
        }
    }
    return $result;
}

function speed_test(string $func_name, callable $test_func, int $size) {
    $multiples_of_2 = range(OFFSET, OFFSET + $size, 2);
    $multiples_of_3 = range(OFFSET, OFFSET + $size, 3);

    export_init($func_name);
    $start_time = microtime(true);

    for ($i = 0; $i < REPEAT_TIME; $i++) {
        $result = $test_func($multiples_of_2, $multiples_of_3);
    }

    $end_time = microtime(true);
    export_result($start_time, $end_time);
    examine(count($result), intdiv($size, 6) + 1);
}

function speed_test_cached(string $func_name, callable $test_func, int $size) {
    $multiples_of_2 = range(OFFSET, OFFSET + $size, 2);
    $multiples_of_3 = range(OFFSET, OFFSET + $size, 3);
    $flipped = array_flip($multiples_of_3);

    export_init($func_name);
    $start_time = microtime(true);

    for ($i = 0; $i < REPEAT_TIME; $i++) {
        $result = $test_func($multiples_of_2, $flipped);
    }

    $end_time = microtime(true);
    export_result($start_time, $end_time);
    examine(count($result), intdiv($size, 6) + 1);
}

function export_init(string $func_name) {
    echo "----- " . $func_name . " -----" . PHP_EOL;
    echo "Start: " . date("Y/m/d H:i:s") . PHP_EOL;
}

function export_result(float $start_time, float $end_time) {
    $diff = $end_time - $start_time;
    echo "End: " . date("Y/m/d H:i:s") . PHP_EOL;
    echo "Elapsed time: " . sprintf("%.4f", $diff) . "s" . PHP_EOL;
    echo "Speed: " . sprintf("%.4f", 1000 * $diff / REPEAT_TIME) . "ms per time" . PHP_EOL;
}

function examine(int $actual_size, int $expected_size) {
    if ($actual_size !== $expected_size) {
        echo "ERROR: wrong function" . PHP_EOL;
        echo "    Expected array size: " . (string)$expected_size . PHP_EOL;
        echo "    Actual array size: " . (string)$actual_size . PHP_EOL;
        exit(0);
    }
}

echo "Loop: " . REPEAT_TIME . "time" . PHP_EOL;
foreach (SIZES as $size) {
    echo "Array size: {$size}" . PHP_EOL;
    speed_test('flip_isset', 'intersect_flip_isset', $size);
    speed_test_cached('flip_isset_cached', 'intersect_flip_isset_cached', $size);
    echo PHP_EOL;
}
