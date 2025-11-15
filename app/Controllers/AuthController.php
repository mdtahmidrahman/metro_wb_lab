<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Mailer;
use App\Models\User;

class AuthController extends Controller {
    public function showLogin() {
      
        $this->view('auth/login.php');
    }

    public function showRegister() {
        $this->view('auth/register.php');
    }

    public function register() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
            $this->view('auth/register.php', ['error' => $error]);
            return;
        }
        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
            $this->view('auth/register.php', ['error' => $error]);
            return;
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        User::create($name, $email, $hashed);

        // send welcome email (Mailtrap for dev)
        Mailer::send($email, 'Welcome to AuthBoard', "Hello $name,\n\nThanks for registering at AuthBoard.");

        header('Location: /login');
        exit;
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password']))
        {
            $sessionUser = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'avatar' => $user['avatar'] ?? null,
                'bio' => $user['bio'] ?? null,
            ];

            Session::set('user', $sessionUser);
            header('Location: /dashboard');
            exit;
        }

        // Show error message
        $error = 'Invalid credentials.';
        $this->view('auth/login.php', ['error' => $error]);
    }

    public function logout() {
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
