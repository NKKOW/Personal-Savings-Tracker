<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repositories/UsersRepository.php';

class SecurityController extends AppController {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            $redirect = ($_SESSION['user_role'] === 'admin') ? 'admin' : 'dashboard';
            header("Location: {$url}/{$redirect}");
            return;
        }

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

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = ($user['email'] === 'admin@savingszen.local') ? 'admin' : 'user';
        
        $url = "http://$_SERVER[HTTP_HOST]";
        $redirect = ($_SESSION['user_role'] === 'admin') ? 'admin' : 'dashboard';
        header("Location: {$url}/{$redirect}");
        return;
    }

    public function register() {
        if ($this->isPost()) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordRepeat = $_POST['password_repeat'] ?? '';
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');

            if (empty($email) || empty($password) || empty($passwordRepeat) || empty($firstName) || empty($lastName)) {
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
            $username = $firstName;
            $fullName = $firstName . ' ' . $lastName;

            $userRepository->createUser($username, $email, $hashedPassword, $fullName);

            return $this->render('login', ['messages' => 'Rejestracja udana. Możesz się zalogować.']);
        }

        return $this->render("register");
    }

    public function logout() {
        session_unset();
        session_destroy();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
        return;
    }
}