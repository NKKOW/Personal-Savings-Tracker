<?php
require_once 'AppController.php';

class SecurityController extends AppController {
    public function login() {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($email) || empty($password)) {
            return $this->render('login', ['messages' => 'Fill all fields']);
        }

        if ($email !== 'user@savings.com' || $password !== 'admin') {
            return $this->render('login', ['messages' => 'Wrong password or email']);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        return;
    }
}