<?php
// models/Category.php
class Category
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($name, $imagePath)
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (category, image_path) VALUES (:name, :image)");
        return $stmt->execute([
            ':name' => trim($name),
            ':image' => trim($imagePath)
        ]);
    }

    public function update($id, $name, $imagePath)
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET category = :name, image_path = :image WHERE id = :id");
        return $stmt->execute([
            ':name' => trim($name),
            ':image' => trim($imagePath),
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
