<?php

namespace Apsl\Mvc\Task;


abstract class Task
{
    const RETURN_SUCCESS = 0;
    const RETURN_CLASS_NOT_SPECIFIED = 1;
    const RETURN_CLASS_DOES_NOT_EXIST = 2;
    const RETURN_CLASS_DOES_NOT_EXTEND_TASK = 3;
    const RETURN_EXECUTION_ERROR = -1;

    protected array $services;
    protected array $cliArgs;

    public function __construct()
    {
        global $argv;
        $this->cliArgs = array_slice($argv, 2);

        $this->services = require 'config/services.php';
    }

    abstract public function execute(): int;

    public function out(string $message, bool $withNewLine = true): void
    {
        echo $message . ($withNewLine ? "\n" : '');
    }
}
