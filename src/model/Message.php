<?php
class Message {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($content, $pseudo, $subject_id) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO messages (content, pseudo, subject_id) VALUES (:content, :pseudo, :subject_id)"
        );
        return $stmt->execute([
            'content' => $content,
            'pseudo' => $pseudo,
            'subject_id' => $subject_id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM messages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getBySubject($subject_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE subject_id = :subject_id ORDER BY date ASC");
        $stmt->execute(['subject_id' => $subject_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

