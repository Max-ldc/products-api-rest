<?php

namespace App\Controller;

use App\Crud\ProductsCrud;
use App\Exception\APIException;
use App\Exception\UnprocessableContentException;
use App\Http\ResponseCode;
use PDO;

class ProductsApiCrudController
{
    private ProductsCrud $productsCrud;
    private const ACCEPTED_METHODS = ["GET", "POST", "PUT", "DELETE"];

    public function __construct(
        private PDO $pdo,
        private string $uri,
        private string $httpMethod
    ) {
        $this->checkHttpMethod();
        $this->productsCrud = new ProductsCrud($pdo);
    }

    public function handle(): void
    {
        if ($this->uri === "/products") {
            $this->collectionOperation();
        } else {
            // on récupère l'id derrière /products/
            $uriExploded = explode('/', $this->uri);
            $id = intval(end($uriExploded));
            // on le teste
            $this->checkCorrectId($id);
            // S'il est bien dans la BDD :
            $this->ProductOperation($id);
        }
    }

    private function checkHttpMethod(): void
    {
        if (!in_array($this->httpMethod, self::ACCEPTED_METHODS)) {
            throw new APIException("Please use an accepted method : " . implode(" - ", self::ACCEPTED_METHODS), ResponseCode::METHOD_NOT_ALLOWED);
        }
    }

    private function checkCorrectId(int $id): void
    {
        if ($id === 0 || $this->productsCrud->getProduct($id) === null) {
            throw new APIException("Product not found", ResponseCode::NOT_FOUND);
            exit;
        }
    }

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
                $products = $this->productsCrud->getProductsList();
                echo json_encode($products);
                break;
            case "POST":
                // On récupère les data et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on a toutes les datas du produit, on va le créer :
                $this->productsCrud->create($data);
                http_response_code(ResponseCode::CREATED);
                break;
            default:
                throw new APIException("Please use GET or POST method for a collection operation", ResponseCode::METHOD_NOT_ALLOWED);
        }
    }

    private function ProductOperation(int $id): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $product = $this->productsCrud->getProduct($id); 
                echo json_encode($product);
                break;
            case "PUT":
                // On récupère les datas et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on les a :
                $this->productsCrud->putProduct($id, $data);
                http_response_code(ResponseCode::NO_CONTENT);
                break;
            case "DELETE":
                $this->productsCrud->deleteProduct($id);
                http_response_code(ResponseCode::NO_CONTENT);
                break;
            default:
                throw new APIException("Please use GET, PUT or DELETE metho for a product operation", ResponseCode::METHOD_NOT_ALLOWED);
        }
    }
}
