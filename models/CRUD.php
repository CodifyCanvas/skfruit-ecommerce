<?php
// models/CRUD.php

class CRUD
{
    private $pdo;

    // <== Constructor: Dependency Injection for PDO ==>
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --------------------------------------------------
    // <== CREATE a new record ==> 
    // @param array $data        - column => value pairs
    // @param string $tableName  - target table
    // @return int|false         - inserted ID or false
    // --------------------------------------------------
    public function create(array $data, string $tableName): int|false
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);

        // <== Sanitize Parameters ==>
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = is_string($value) ? trim($value) : $value;
        }

        // <== Execute and return insert ID ==>
        if ($stmt->execute($params)) {
            return (int) $this->pdo->lastInsertId();
        }

        return false;
    }

    // --------------------------------------------------
    // <== READ all records from a table ==>
    // @param string $tableName
    // @return array
    // --------------------------------------------------
    public function getAll(string $tableName): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$tableName} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------
    // <== UPDATE record by column match (e.g., id) ==>
    // @param array $data
    // @param string $columnName
    // @param mixed $columnValue
    // @param string $tableName
    // @return bool
    // --------------------------------------------------
    public function update(array $data, string $columnName, $columnValue, string $tableName): bool
    {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "$column = :$column";
        }

        $setString = implode(", ", $setParts);

        $sql = "UPDATE {$tableName} SET {$setString} WHERE {$columnName} = :conditionValue";
        $stmt = $this->pdo->prepare($sql);

        // <== Prepare Params ==>
        $params = [];
        foreach ($data as $key => $value) {
            $params[":$key"] = is_string($value) ? trim($value) : $value;
        }
        $params[':conditionValue'] = $columnValue;

        return $stmt->execute($params);
    }

    // --------------------------------------------------
    // <== DELETE a record by column value ==>
    // @param string $columnName
    // @param mixed $columnValue
    // @param string $tableName
    // @return bool
    // --------------------------------------------------
    public function delete(string $columnName, $columnValue, string $tableName): bool
    {
        $sql = "DELETE FROM {$tableName} WHERE {$columnName} = :value";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':value' => $columnValue]);
    }

    // --------------------------------------------------
    // <== READ record by specific column (e.g., id) ==>
    // @param string $columnName
    // @param mixed $columnValue
    // @param string $tableName
    // @return array|null
    // --------------------------------------------------
    public function getById(string $columnName, $columnValue, string $tableName): ?array
    {
        $sql = "SELECT * FROM {$tableName} WHERE {$columnName} = :value LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':value' => $columnValue]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // --------------------------------------------------
    // <== CHECK for duplicate record by column value ==>
    // @param string $columnName
    // @param mixed $columnValue
    // @param string $tableName
    // @return bool
    // --------------------------------------------------
    public function checkDuplicate(string $columnName, $columnValue, string $tableName): bool
    {
        $sql = "SELECT COUNT(*) FROM {$tableName} WHERE {$columnName} = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':value' => $columnValue]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
}
?>
