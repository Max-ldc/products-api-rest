<?php

namespace App\Controller;

use App\Crud\ApiProductsCrud;
use App\Exception\ExceptionsHandler;
use App\Exception\MethodNotAllowed;
use App\Exception\UnprocessableContentException;
use App\Http\ResponseCode;
use Exception;

class ProductsApiCrudController extends ApiCrudController
{
    // Remonter la fonction checkcorrectId aussi

    public function handle(): void
    {
        $this->Crud = new ApiProductsCrud($this->pdo);
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
                $this->resourceOperation($id);
            } catch (Exception $e) {
                ExceptionsHandler::sendError($e);
            }
        }
    }

    /**
     * Check if all the data informations are informed. Pass without doing anything if it's good, throw an Exception if it's not
     *
     * @param array $data name, base price & description
     * @return void
     * @throws Exception
     */
    public function checkCorrectData(array $data): void
    {
        if (!isset($data['name']) || !isset($data['baseprice']) || !isset($data['description'])) {
            throw new UnprocessableContentException("Name, Base price and Description are required");
            exit;
        }
    }

    public function collectionOperation(): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $products = $this->Crud->getList();
                echo json_encode($products);
                break;
            case "POST":
                // On récupère les data et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on a toutes les datas du produit, on tente de le créer :
                try {
                    $this->Crud->create($data);
                    http_response_code(ResponseCode::CREATED);
                } catch (Exception $e) {
                    ExceptionsHandler::sendError($e);
                }
                break;
            default:
                throw new MethodNotAllowed("Please use GET or POST method for a collection operation");
        }
    }

    public function resourceOperation(int $id): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $product = $this->Crud->get($id);
                echo json_encode($product);
                break;
            case "PUT":
                // On récupère les datas et on les vérifie :
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on les a :
                try {
                    $this->Crud->put($id, $data);
                    http_response_code(ResponseCode::NO_CONTENT);
                } catch (Exception $e) {
                    ExceptionsHandler::sendError($e);
                }
                break;
            case "DELETE":
                try {
                    $this->Crud->delete($id);
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
