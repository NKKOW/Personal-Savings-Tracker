<?php
require_once __DIR__ . '/AppController.php';

class DashboardController extends AppController {
    public function index() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        $title = "SavingsZen - Dashboard";
        $balance = "0.00"; // Z czasem pobierzemy to z bazy
        
        return $this->render("dashboard", [
            "title" => $title, 
            "balance" => $balance
        ]);
    }
}