#!/usr/bin/env php
<?php

const RAND_MAX = 10000;
const NUM_OF_OBJECTS = 1000;
const REPEAT_TIME = 1000;

class SomeObject
{
    /** @var int */
    private $number;

    /** @param int $number */
    public function __construct(int $number) { $this->number = $number; }

    /** @return int */
    public function getNumber(): int { return $this->number; }
}

/** @return SomeObject[] */
function getRandomObjects(): array {
    $result = [];
    foreach (range(1, NUM_OF_OBJECTS) as $i) {
        $num = mt_rand(0, RAND_MAX);
        $result[] = new SomeObject($num);
    }
    return $result;
}

/** @param SomeObject[] $objects */
function assignmentInIf(array $objects): void {
    $result = [];
    foreach ($objects as $object) {
        if (($num = $object->getNumber()) % 2 === 0) {
            $result[] = $num;
        }
    }
    return;
}

/** @param SomeObject[] $objects */
function noAssignment(array $objects): void {
    $result = [];
    foreach ($objects as $object) {
        if ($object->getNumber() % 2 === 0) {
            $result[] = $object->getNumber();
        }
    }
    return;
}

function speed_test(string $func_name, callable $test_func, array $objects) {
    echo "----- " . $func_name . " -----" . PHP_EOL;
    echo "Start: " . date("Y/m/d H:i:s") . PHP_EOL;
    $start_time = microtime(true);

    for ($i = 0; $i < REPEAT_TIME; $i++) {
        $test_func($objects);
    }

    $end_time = microtime(true);
    $diff = $end_time - $start_time;
    echo "End: " . date("Y/m/d H:i:s") . PHP_EOL;
    echo "Elapsed time: " . sprintf("%.4f", $diff) . "s" . PHP_EOL;
    echo "Loop: " . REPEAT_TIME * NUM_OF_OBJECTS . "time" . PHP_EOL;
    echo "Speed: " . sprintf("%.4f", (1000 * 1000 * $diff) / (REPEAT_TIME * NUM_OF_OBJECTS)) . "μs per time" . PHP_EOL;
}

$objects = getRandomObjects();
speed_test('assignment in if', 'assignmentInIf', $objects);
speed_test('no assignment', 'noAssignment', $objects);
