<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/GoalsRepository.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';

class GoalController extends AppController {
    private GoalsRepository $goalsRepository;
    private UsersRepository $usersRepository;

    public function __construct() {
        parent::__construct();
        $this->goalsRepository = new GoalsRepository();
        $this->usersRepository = new UsersRepository();
    }

    public function addGoal() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $type = $_POST['goal_type'] ?? 'fixed';
        $target = ($type === 'fixed' && !empty($_POST['target_amount'])) ? (float)$_POST['target_amount'] : null;
        $initialAmount = !empty($_POST['initial_amount']) ? (float)$_POST['initial_amount'] : 0;

        if (!empty($title)) {
            $goalId = $this->goalsRepository->addGoal($userId, $title, $type, $target);
            
            if ($initialAmount > 0) {
                $this->goalsRepository->transferMoneyToGoal($userId, $goalId, $initialAmount);
            }
        }

        $this->redirect('dashboard');
    }

    public function addMoney() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $goalId = (int)($_POST['goal_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);

        if ($goalId > 0 && $amount > 0) {
            $this->goalsRepository->transferMoneyToGoal($userId, $goalId, $amount);
        }

        $this->redirect('dashboard');
    }

    public function updateBalance() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $amount = (float)($_POST['amount'] ?? 0);

        if ($amount > 0) {
            $this->usersRepository->updateBalance($userId, $amount);
        }

        $this->redirect('dashboard');
    }

    public function withdrawBalance() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $amount = (float)($_POST['amount'] ?? 0);

        if ($amount > 0) {
            $this->usersRepository->updateBalance($userId, -$amount);
        }

        $this->redirect('dashboard');
    }

    public function deleteGoal() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $goalId = (int)($_POST['goal_id'] ?? 0);

        if ($goalId > 0) {
            $this->goalsRepository->deleteGoal($userId, $goalId);
        }

        $this->redirect('dashboard');
    }

    public function spendMoney() {
        if (!$this->isLoggedIn() || !$this->isPost()) return;

        $userId = $_SESSION['user_id'];
        $goalId = (int)($_POST['goal_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);

        if ($goalId > 0 && $amount > 0) {
            $this->goalsRepository->spendFromGoal($userId, $goalId, $amount);
        }

        $this->redirect('dashboard');
    }

    private function redirect(string $path) {
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/{$path}");
        exit();
    }
}