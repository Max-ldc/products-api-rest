<?php

namespace App\Controller;

use App\Crud\ApiCrud;
use App\Exception\MethodNotAllowed;
use App\Exception\NotFound;
use PDO;

abstract class ApiCrudController
{
    protected const ACCEPTED_COLLECTION_METHODS = ["GET", "POST"];
    protected const ACCEPTED_RESOURCE_METHODS = ["GET", "PUT", "DELETE"];

    protected ApiCrud $Crud;

    public function __construct(
        protected PDO $pdo,
        protected string $uri,
        protected string $httpMethod
    ) {
    }

    /**
     * Check if the used method is acceptable for a collection operation. Pass without doing anything if it's good, throw an Exception if it's not.
     *
     * @return void
     * @throws Exception
     */
    protected function checkCollectionMethod(): void
    {
        if (!in_array($this->httpMethod, self::ACCEPTED_COLLECTION_METHODS)) {
            throw new MethodNotAllowed("Please use an accepted method for a collection operation : " . implode(" - ", self::ACCEPTED_COLLECTION_METHODS));
        }
    }

    /**
     * Check if the used method is acceptable for a resource operation. Pass without doing anything if it's good, throw an Exception if it's not. 
     *
     * @return void
     * @throws Exception
     */
    protected function checkResourceMethod(): void
    {
        if (!in_array($this->httpMethod, self::ACCEPTED_RESOURCE_METHODS)) {
            throw new MethodNotAllowed("Please use an accepted method for a resource operation : " . implode(" - ", self::ACCEPTED_RESOURCE_METHODS));
        }
    }

    /**
     * Check if the id is in the database. Pass without doing anything if it's good, throw an Exception if it's not
     *
     * @param integer $id
     * @return void
     * @throws Exception
     */
    protected function checkCorrectId(int $id): void
    {
        if ($id === 0 || $this->Crud->get($id) === null) {
            throw new NotFound("Product not found");
            exit;
        }
    }

    abstract function checkCorrectData(array $data): void;

    abstract function collectionOperation(): void;

    abstract function resourceOperation(int $id): void;
}
