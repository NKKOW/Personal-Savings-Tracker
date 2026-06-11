<?php
require_once 'Repository.php';

class UsersRepository extends Repository {
    public function getUsers(): ?array {
        $query = $this->database->connect()->prepare(
            "SELECT * FROM users;"
        );
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail(string $email) {
        $query = $this->database->connect()->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $query->bindParam(':email', $email);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(string $username, string $email, string $hashedPassword, string $fullName) {
        $query = $this->database->connect()->prepare(
            "INSERT INTO users (username, email, password, full_name, is_active) VALUES (?, ?, ?, ?, true)"
        );
        $query->execute([
            $username,
            $email,
            $hashedPassword,
            $fullName
        ]);
    }

    public function searchUsers(string $searchTerm): array {
        $query = $this->database->connect()->prepare(
            "SELECT * FROM users 
            WHERE username LIKE :search OR email LIKE :search OR full_name LIKE :search"
        );
        $likeTerm = '%' . $searchTerm . '%';
        $query->bindParam(':search', $likeTerm);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser(int $id): bool {
        $query = $this->database->connect()->prepare(
            "DELETE FROM users WHERE id = :id"
        );
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function getUserById(int $id) {
        $pdo = $this->database->connect();

        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS balance NUMERIC(10, 2) DEFAULT 0.00 CHECK (balance >= 0)");
            
            $pdo->exec("CREATE TABLE IF NOT EXISTS goals (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                title VARCHAR(255) NOT NULL,
                goal_type VARCHAR(20) NOT NULL DEFAULT 'fixed',
                target_amount NUMERIC(10, 2),
                current_amount NUMERIC(10, 2) DEFAULT 0.00 CHECK (current_amount >= 0),
                created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (PDOException $e) {
        }

        $stmt = $pdo->prepare('
            SELECT id, email, username, full_name, balance, is_active FROM users WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBalance(int $userId, float $amount): bool {
        try {
            $stmt = $this->database->connect()->prepare('
                UPDATE users SET balance = balance + :amount WHERE id = :id
            ');
            $stmt->execute(['amount' => $amount, 'id' => $userId]);
            return true;
        } catch (PDOException $e) {
            return false; 
        }
    }
}