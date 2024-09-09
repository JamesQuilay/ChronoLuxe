<?php

class Watch {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listWatches($limit = 8, $offset = 0) {
        $sql = "SELECT id, model_name, price, description, stock_quantity, image, status, watch_category 
                FROM watches 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalWatches() {
        $sql = "SELECT COUNT(*) AS total FROM watches";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function createWatch($data) {
        $query = "INSERT INTO watches (model_name, price, stock_quantity, description, status, image, watch_category) 
                  VALUES (:model_name, :price, :stock_quantity, :description, :status, :image, :watch_category)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':model_name', $data['model_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock_quantity', $data['stock_quantity']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':watch_category', $data['watch_category']);
        $stmt->execute();
    }

    public function getWatch($id) {
        $query = "SELECT * FROM watches WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateWatch($id, $data) {
        $query = "UPDATE watches SET model_name = :model_name, price = :price, stock_quantity = :stock_quantity, watch_category = :watch_category,
                  description = :description, status = :status, image = :image WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':model_name', $data['model_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock_quantity', $data['stock_quantity']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':watch_category', $data['watch_category']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteWatch($id) {
        echo "Executing delete query for ID: " . htmlspecialchars($id);
        $query = "DELETE FROM watches WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "Query executed.";
    }

    public function countWatches() {
        $sql = "SELECT COUNT(*) AS total FROM watches";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    
    
    
}

?>
