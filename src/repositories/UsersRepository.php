<?php
require_once 'Repository.php';

class UsersRepository extends Repository {
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
}