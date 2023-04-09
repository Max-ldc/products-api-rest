<?php

namespace App\Crud;

use Exception;
use PDO;
use PDOException;

class ProductsCrud
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getProductsList(): ?array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        return ($products === false) ? null : $products;
    }

    /**
     * create a new product
     *
     * @param array $data name, baseprice & description
     * @return void
     * @throws Exception
     */
    public function create(array $data): void
    {
        $query = "INSERT INTO products VALUES (null, :product_name, :base_price, :desc_product)";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'product_name' => $data['name'],
            'base_price' => $data['baseprice'],
            'desc_product' => $data['description']
        ]);
        if ($stmt->rowCount() === 0) {
            throw new Exception('Aucune ligne n\'a pu être enregistrée');
        }
    }

    public function getProduct(int $id): ?array
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        return ($product === false) ? null : $product;
    }

    public function putProduct(int $id, array $data): void
    {
        $query = "UPDATE products SET name = :name_product, priceHT = :price_ht, description = :desc_product WHERE id = :id;";

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'name_product' => $data['name'],
                'price_ht' => $data['baseprice'],
                'desc_product' => $data['description'],
                'id' => $id
        ]);
        } catch (PDOException $e) {
            
        }
    }

    public function deleteProduct(int $id): void
    {
        $query = "DELETE FROM products WHERE id = :id;";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new Exception('Aucune ligne n\'a pu être supprimée');
        }
    }
}
