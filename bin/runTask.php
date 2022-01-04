#!/usr/local/bin/php
<?php

chdir(__DIR__ . DIRECTORY_SEPARATOR . '..');

require_once 'vendor/autoload.php';

use Apsl\Mvc\Task\Task;


if (!isset($argv[1])) {
    echo "You need to specify full class name to be run as first parameter.\n";
    exit(Task::RETURN_CLASS_NOT_SPECIFIED);
}

$class = $argv[1];
if (!class_exists($class)) {
    echo "Class {$class} does not exist.\n";
    exit(Task::RETURN_CLASS_DOES_NOT_EXIST);
}

$task = new $class();
if (!($task instanceof Task)) {
    $taskClass = Task::class;
    echo "Class {$class} does not extend {$taskClass}.\n";
    exit(Task::RETURN_CLASS_DOES_NOT_EXTEND_TASK);
}

$returnCode = $task->execute();

exit($returnCode);
