<?php
// models/Category.php
class Offer
{
    private $pdo;

    // <== Constructor: Inject PDO connection ==>
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --------------------------------------------------
    // <== Fetch all products for select dropdown ==>
    // @return array - id and title of products
    // --------------------------------------------------
    public function getProductsForSelectInput()
    {
        $stmt = $this->pdo->query("SELECT id, title FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
