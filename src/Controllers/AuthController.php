<?php
namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController {
    public function registerForm(): void {
        $pageTitle = 'Inscription';
        $this->render('auth/register', ['pageTitle' => $pageTitle]);
    }
    
    public function registerSubmit(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (empty($username) || empty($password) || empty($confirm)) {
            $error = "Tous les champs sont obligatoires";
            $this->render('auth/register', ['pageTitle' => 'Inscription', 'error' => $error]);
            return;
        }
        if ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas";
            $this->render('auth/register', ['pageTitle' => 'Inscription', 'error' => $error]);
            return;
        }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $user = User::createUser($username, $hashed);
        if ($user) {
            $_SESSION['user_id'] = $user->getIdUser();
            $this->redirect('');
        } else {
            $error = "Erreur lors de l'inscription";
            $this->render('auth/register', ['pageTitle' => 'Inscription', 'error' => $error]);
        }
    }
    
    public function showLoginForm(): void {
        $pageTitle = 'Connexion';
        $this->render('auth/login', ['pageTitle' => $pageTitle]);
    }
    
    public function login(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = User::findByUsername($username);
        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user_id'] = $user->getIdUser();
            $this->redirect('');
        } else {
            $error = "Identifiants invalides";
            $this->render('auth/login', ['pageTitle' => 'Connexion', 'error' => $error]);
        }
    }
    
    public function logout(): void {
        session_destroy();
        $this->redirect('');
    }
}
