<?php

namespace Apsl\Mvc\Database;


class Connection
{
    protected \PDO $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new \PDO($config['dsn'], $config['user'], $config['pass']);
    }

    public function prepare(string $sql, array $params = []): \PDOStatement
    {
        return $this->pdo->prepare($sql, $params);
    }

    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}
