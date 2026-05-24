<?php
require_once __DIR__ . '/AppController.php';

class DashboardController extends AppController {
    public function index() {
        if (!$this->isLoggedIn()) {
            return;
        }
        
        return $this->render("dashboard");
    }
}