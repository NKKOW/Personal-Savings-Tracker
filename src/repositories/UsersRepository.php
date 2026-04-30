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

    public function createUser(string $email, string $hashedPassword) {
        $query = $this->database->connect()->prepare(
            "INSERT INTO users (email, password) VALUES (?, ?)"
        );
        $query->execute([
            $email,
            $hashedPassword
        ]);
    }
}