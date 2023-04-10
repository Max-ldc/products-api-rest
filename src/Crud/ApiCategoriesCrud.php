<?php

namespace App\Crud;

use App\Exception\InternalServerError;

class ApiCategoriesCrud extends ApiCrud
{
    /**
     * Create a new category. Pass without doing anything if it's ok, throw an Exception if it's not.
     *
     * @param array $data name, description
     * @return void
     * @throws Exception
     */
    public function create(array $data): void
    {
        $query = "INSERT INTO categories VALUES (null, :name_category, :desc_category);";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'name_category' => $data['name'],
            'desc_category' => ($data['description'] ?? null)
        ]);
        if ($stmt->rowCount() === 0) {
            throw new InternalServerError("No line could be registered");
        }
    }

    public function getList(): ?array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll();

        return ($categories === false) ? null : $categories;
    }

    /**
     * Return an array of the category informations, or null if the category is unfound
     *
     * @param integer $id
     * @return array|null
     */
    public function get(int $id): ?array
    {
        $query = "SELECT * FROM categories WHERE id = :id;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        return ($category === false) ? null : $category;
    }

    /**
     * Modifies an existing category. Pass without doing anything if it's ok, throw an Exception if it's not.
     *
     * @param integer $id
     * @param array $data name, description
     * @return void
     * @throws Exception
     */
    public function put(int $id, array $data): void
    {
        $query = "UPDATE categories SET name = :name_category, description = :desc_category WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'name_category' => $data['name'],
            'desc_category' => ($data['description'] ?? null),
            'id' => $id
        ]);
        if ($stmt->rowCount() === 0) {
            throw new InternalServerError('No line could be modified');
        }
    }

    /**
     * Deletes an existing category. Pass without doing anything if it's ok, throw an Exception if it's not.
     *
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM categories WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new InternalServerError('No line could be deleted');
        }
    }
}
