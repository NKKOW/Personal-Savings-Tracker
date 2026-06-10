<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';
require_once __DIR__ . '/../repositories/GoalsRepository.php';

class DashboardController extends AppController {
    public function index() {
        if (!$this->isLoggedIn()) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $usersRepo = new UsersRepository();
        $goalsRepo = new GoalsRepository();
        
        $user = $usersRepo->getUserById($userId);
        $goals = $goalsRepo->getGoalsByUserId($userId);
        
        $freeBalance = (float)$user['balance'];
        $goalsTotal = 0;
        
        foreach ($goals as $goal) {
            $goalsTotal += (float)$goal['current_amount'];
        }
        
        $totalBalance = $freeBalance + $goalsTotal;
        
        return $this->render("dashboard", [
            "title" => "SavingsZen - Dashboard", 
            "freeBalance" => number_format($freeBalance, 2, '.', ''),
            "totalBalance" => number_format($totalBalance, 2, '.', ''),
            "goals" => $goals
        ]);
    }
}