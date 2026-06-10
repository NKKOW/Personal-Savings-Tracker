<?php
require_once 'Repository.php';

class GoalsRepository extends Repository {
    
    public function getGoalsByUserId(int $userId): array {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM goals WHERE user_id = :user_id ORDER BY created_at DESC
        ');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGoal(int $userId, string $title, string $goalType, ?float $targetAmount): int {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO goals (user_id, title, goal_type, target_amount)
            VALUES (?, ?, ?, ?) RETURNING id
        ');
        $stmt->execute([$userId, $title, $goalType, $targetAmount]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    public function transferMoneyToGoal(int $userId, int $goalId, float $amount): bool {
        $pdo = $this->database->connect();
        
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare('UPDATE users SET balance = balance - :amount WHERE id = :user_id');
            $stmt1->execute(['amount' => $amount, 'user_id' => $userId]);

            $stmt2 = $pdo->prepare('UPDATE goals SET current_amount = current_amount + :amount WHERE id = :goal_id AND user_id = :user_id');
            $stmt2->execute(['amount' => $amount, 'goal_id' => $goalId, 'user_id' => $userId]);

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }
}