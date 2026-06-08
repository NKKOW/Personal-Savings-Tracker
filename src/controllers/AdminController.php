<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';

class AdminController extends AppController {
    public function index() {
        if (!$this->isLoggedIn()) {
            return;
        }

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/dashboard");
            return;
        }
        
        $title = "SavingsZen - Admin Panel";
        $usersRepository = new UsersRepository();
        $users = $usersRepository->getUsers();

        return $this->render("admin", ["title" => $title, "users" => $users]);
    }
}