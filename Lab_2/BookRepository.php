<?php
// BookRepository.php
class BookRepository {
    private $db;

    // Dependency injection of our working PDO connection
    public function __construct(PDO $pdoConnection) {
        $this->db = $pdoConnection;
    }

    public function getBooksByGenre($genreName) {
        // Task 3
        $sql = "SELECT * FROM books WHERE genre = :genreName ORDER BY title ASC";

        try {
            $stmt = $this->db->prepare($sql);

            // Task 4
            $stmt->bindValue(':genreName', $genreName);

            $stmt->execute();
            return $stmt->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}