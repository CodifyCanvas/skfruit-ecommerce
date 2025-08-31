<?php
// models/Product.php
class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($categoryId, $title, $description, $price, $stock, $imagePath)
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (category_id, title, description, price, stock, image_path) VALUES (:category_id, :title, :description, :price, :stock, :image_path)");
        return $stmt->execute([
             ':category_id' => $categoryId,
            ':title' => trim($title),
            ':description' => trim($description),
            ':price' => $price,
            ':stock' => $stock,
            ':image_path' => trim($imagePath)
        ]);
    }

    public function update($id, $categoryId, $title, $description, $stock, $price, $imagePath)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET category_id = :category_id, title = :title, description = :description, price = :price, stock = :stock, image_path = :image_path WHERE id = :id");
        return $stmt->execute([
            ':category_id' => $categoryId,
            ':title' => trim($title),
            ':description' => trim($description),
            ':price' => $price,
            ':stock' => $stock,
            ':image_path' => trim($imagePath),
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
