<?php
class Subject {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($title, $pseudo) {
        $stmt = $this->pdo->prepare("INSERT INTO subjects (title, pseudo) VALUES (:title, :pseudo)");
        $stmt->execute(['title' => $title, 'pseudo' => $pseudo]);
        return (int)$this->pdo->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM subjects WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM subjects ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
