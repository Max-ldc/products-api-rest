<?php

namespace App\Controller;

use App\Crud\ApiCategoriesCrud;
use App\Exception\ExceptionsHandler;
use App\Exception\MethodNotAllowed;
use App\Exception\UnprocessableContentException;
use App\Http\ResponseCode;
use Exception;

class CategoriesApiCrudController extends ApiCrudController
{

    public function handle(): void
    {
        $this->Crud = new ApiCategoriesCrud($this->pdo);
        if ($this->uri === "/categories") {
            try {
                // On teste si la méthode correspond pour une opération sur la collection :
                $this->checkCollectionMethod();
                $this->collectionOperation();
            } catch (Exception $e) {
                ExceptionsHandler::sendError($e);
            }
        } else {
            try {
                // On teste si la méthode correspond pour une opération sur la ressource :
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
     * Check if the category name is informed. Pass without doing anything if it's good, throw an Exception if it's not
     *
     * @param array $data name
     * @return void
     * @throws Exception
     */
    public function checkCorrectData(array $data): void
    {
        if (!isset($data['name'])) {
            throw new UnprocessableContentException("Name is required");
            exit;
        }
    }

    public function collectionOperation(): void
    {
        switch ($this->httpMethod) {
            case "GET":
                $categories = $this->Crud->getList();
                echo json_encode($categories);
                break;
            case "POST":
                try {
                    // On récupère les data et on les vérifie
                    $data = json_decode(file_get_contents("php://input"), true);
                    $this->checkCorrectData($data);
                    // Si on a la bonne data pour une catégorie (le nom), on tente de la créer :
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
                $category = $this->Crud->get($id);
                echo json_encode($category);
                break;
            case "PUT":
                // On récupère les data et on les vérifie
                $data = json_decode(file_get_contents("php://input"), true);
                $this->checkCorrectData($data);
                // Si on a la bonne data pour une catégorie (le nom), on tente de la créer :
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
