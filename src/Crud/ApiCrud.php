<?php

namespace App\Crud;

use PDO;

abstract class ApiCrud
{
    public function __construct(protected PDO $pdo)
    {
    }

    abstract function create(array $data): void;

    abstract function getList(): ?array;

    abstract function get(int $id): ?array;

    abstract function put(int $id, array $data): void;

    abstract function delete(int $id): void;
}
