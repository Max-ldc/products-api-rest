<?php

namespace App\Controller;

use App\Crud\ProductsCrud;
use App\Exception\ExceptionsHandler;
use App\Exception\MethodNotAllowed;
use App\Exception\NotFound;
use App\Exception\UnprocessableContentException;
use App\Http\ResponseCode;
use Exception;
use PDO;

class ProductsApiCrudController
{
    private ProductsCrud $productsCrud;
    private const ACCEPTED_COLLECTION_METHODS = ["GET", "POST"];
    private const ACCEPTED_RESOURCE_METHODS = ["GET", "PUT", "DELETE"];

    public function __construct(
        private PDO $pdo,
        private string $uri,
        private string $httpMethod
    ) {
        $this->productsCrud = new ProductsCrud($pdo);
    }

    public function handle(): void
    {
        if ($this->uri === "/products") {
            try {
                // On teste si la méthode correspond pour une opération sur la collection :
                $this->checkCollectionMethod();
                $this->collectionOperation();
            } catch (Exception $e) {
                ExceptionsHandler::sendError($e);
            }
        } else {
            try {
                // On teste si la méthode correspond pour une opération sur la collection :
                $this->checkResourceMethod();
                // on récupère l'id derrière /products/
                $uriExploded = explode('/', $this->uri);
                $id = intval(end($uriExploded));
                // on le teste
                $this->checkCorrectId($id);
                // S'il est bien dans la BDD :
                $this->ProductOperation($id);
            } catch (Exception $e) {
                ExceptionsHandler::sendError($e);
            }
        }
    }

    /**
     * Check if the used method is acceptable for a collection operation. Pass without doing anything if it's good, throw an Exception if it's not.
     *
     * @return void
     * @throws Exception
     */
    private function checkCollectionMethod(): void
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
    private function checkResourceMethod(): void
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
    private function checkCorrectId(int $id): void
    {
        if ($id === 0 || $this->productsCrud->get($id) === null) {
            throw new NotFound("Product not found");
            exit;
        }
    }

    /**
     * Check if all the data informations are informed. Pass without doing anything if it's good, throw an Exception if it's not
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function checkCorrectData(array $data): void
    {
        if (!isset($data['name']) || !isset($data['baseprice']) || !isset($data['description'])) {
            throw new UnprocessableContentException("Name, Base price and Description are required");
            exit;
        }
    }

    private function collectionOperation(): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $products = $this->productsCrud->getList();
                echo json_encode($products);
                break;
            case "POST":
                // On récupère les data et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on a toutes les datas du produit, on tente de le créer :
                try {
                    $this->productsCrud->create($data);
                    http_response_code(ResponseCode::CREATED);
                } catch (Exception $e) {
                    ExceptionsHandler::sendError($e);
                }
                break;
            default:
                throw new MethodNotAllowed("Please use GET or POST method for a collection operation");
        }
    }

    private function ProductOperation(int $id): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $product = $this->productsCrud->get($id);
                echo json_encode($product);
                break;
            case "PUT":
                // On récupère les datas et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on les a :
                try {
                    $this->productsCrud->put($id, $data);
                    http_response_code(ResponseCode::NO_CONTENT);
                } catch (Exception $e) {
                    ExceptionsHandler::sendError($e);
                }
                break;
            case "DELETE":
                try {
                    $this->productsCrud->delete($id);
                    http_response_code(ResponseCode::NO_CONTENT);
                } catch (Exception $e) {
                    ExceptionsHandler::sendError($e);
                }
                break;
            default:
                throw new MethodNotAllowed("Please use GET, PUT or DELETE metho for a product operation");
        }
    }
}
