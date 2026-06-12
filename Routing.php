<?php
require_once __DIR__ . '/src/controllers/SecurityController.php';
require_once __DIR__ . '/src/controllers/DashboardController.php';
require_once __DIR__ . '/src/controllers/UsersController.php';
require_once __DIR__ . '/src/controllers/AdminController.php';
require_once __DIR__ . '/src/controllers/GoalController.php';

class Routing {
    public static $routes = [
        "login" => ["controller" => "SecurityController", "action" => "login"],
        "register" => ["controller" => "SecurityController", "action" => "register"],
        "logout" => ["controller" => "SecurityController", "action" => "logout"],
        "dashboard" => ["controller" => "DashboardController", "action" => "index"],
        "admin" => ["controller" => "AdminController", "action" => "index"],
        "search" => ["controller" => "UsersController", "action" => "search"],
        "delete-user" => ["controller" => "UsersController", "action" => "delete"],
        
        "add-goal" => ["controller" => "GoalController", "action" => "addGoal"],
        "add-money" => ["controller" => "GoalController", "action" => "addMoney"],
        "update-balance" => ["controller" => "GoalController", "action" => "updateBalance"],
        "withdraw-balance" => ["controller" => "GoalController", "action" => "withdrawBalance"],
        "delete-goal" => ["controller" => "GoalController", "action" => "deleteGoal"],
        "spend-money" => ["controller" => "GoalController", "action" => "spendMoney"],
        
        "" => ["controller" => "SecurityController", "action" => "login"]
    ];

    public static function run(string $path) {
        if (array_key_exists($path, self::$routes)) {
            $controller = self::$routes[$path]["controller"];
            $action = self::$routes[$path]["action"];

            $controllerObj = new $controller;
            $controllerObj->$action();
        } else {
            http_response_code(404);
            include __DIR__ . '/public/views/404.html';
        }
    }
}