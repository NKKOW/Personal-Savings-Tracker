<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';

class DashboardController extends AppController {
    public function index() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        $title = "SavingsZen - Users Index";
        $usersRepository = new UsersRepository();
        $users = $usersRepository->getUsers();

        return $this->render("index", ["title" => $title, "users" => $users]);
    }
}