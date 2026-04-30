<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';

class SecurityController extends AppController {
    public function login() {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if (empty($email) || empty($password)) {
            return $this->render('login', ['messages' => 'Wypełnij wszystkie pola']);
        }

        $userRepository = new UsersRepository();
        $user = $userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render('login', ['messages' => 'Nie znaleziono użytkownika']);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->render('login', ['messages' => 'Błędne hasło']);
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];
        
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        return;
    }

    public function register() {
        if ($this->isPost()) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordRepeat = $_POST['password_repeat'] ?? '';

            if (empty($email) || empty($password) || empty($passwordRepeat)) {
                return $this->render('register', ['messages' => 'Wypełnij wszystkie pola']);
            }

            if ($password !== $passwordRepeat) {
                return $this->render('register', ['messages' => 'Hasła się nie zgadzają']);
            }

            $userRepository = new UsersRepository();
            $user = $userRepository->getUserByEmail($email);
            
            if ($user) {
                return $this->render('register', ['messages' => 'Taki użytkownik już istnieje']);
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $userRepository->createUser($email, $hashedPassword);

            return $this->render('login', ['messages' => 'Rejestracja udana. Możesz się zalogować.']);
        }

        return $this->render("register");
    }
}