#!/usr/bin/env php
<?php

const SIZES = [10 ** 1, 10 ** 2, 10 ** 3, 10 ** 4, 10 ** 5,];
const REPEAT_TIME = 1000;

function standard_func(array $array) {
    return array_sum($array);
}

function raw_loop_sum(array $array) {
    $result = 0;
    foreach ($array as $val) {
        $result += $val;
    }
    return $result;
}

function speed_test(string $func_name, callable $test_func, int $size) {
    $random_element_array = [];
    for ($i = 0; $i < $size; $i++) {
        $random_element_array[] = mt_rand() / mt_getrandmax();
    }

    echo "----- " . $func_name . " -----" . PHP_EOL;
    echo "Start: " . date("Y/m/d H:i:s") . PHP_EOL;
    $start_time = microtime(true);

    for ($i = 0; $i < REPEAT_TIME; $i++) {
        $result = $test_func($random_element_array);
    }

    $end_time = microtime(true);
    $diff = $end_time - $start_time;
    echo "End: " . date("Y/m/d H:i:s") . PHP_EOL;
    echo "Elapsed time: " . sprintf("%.4f", $diff) . "s" . PHP_EOL;
    echo "Speed: " . sprintf("%.4f", 1000 * $diff / REPEAT_TIME) . "ms per time" . PHP_EOL;
}

echo "Loop: " . REPEAT_TIME . "time" . PHP_EOL;
foreach (SIZES as $size) {
    echo "Array size: {$size}" . PHP_EOL;
    speed_test('raw_loop_sum', 'raw_loop_sum', $size);
    speed_test('standard_func', 'standard_func', $size);
    echo PHP_EOL;
}
