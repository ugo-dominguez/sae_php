<?php
namespace App\Controllers;

use App\Config\Requests;
use App\Config\Database;

class AuthController extends BaseController {
    public function registerForm(): void {
        $pageTitle = 'Inscription';

        $this->render('auth/register', [
            'pageTitle' => $pageTitle
        ]);
    }
    
    public function registerSubmit(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($password) || empty($confirm)) {
            $error = "Tous les champs sont obligatoires";

            $this->render('auth/register', [
                'pageTitle' => 'Inscription', 
                'error' => $error
            ]);
            return;
        }

        if ($password !== $confirm) {
            $error = "Les mots de passe ne correspondent pas";

            $this->render('auth/register', [
                'pageTitle' => 'Inscription', 
                'error' => $error
            ]);
            return;
        }
        
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        Requests::getConnection();
        Database::insertUser($username, $hashed);
        $user = Requests::getUserByUsername($username);

        if ($user) {
            $_SESSION['user_id'] = $user->id;
            $this->redirect('');
        } else {
            $error = "Erreur lors de l'inscription";

            $this->render('auth/register', [
                'pageTitle' => 'Inscription', 
                'error' => $error
            ]);
        }
    }
    
    public function showLoginForm(): void {
        $pageTitle = 'Connexion';

        $this->render('auth/login', [
            'pageTitle' => $pageTitle
        ]);
    }
    
    public function login(): void {
        $username = strtolower(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';
    
        error_log("Tentative de connexion pour username: '{$username}'");
        
        Requests::getConnection();
        $user = Requests::getUserByUsername($username);

        if (!$user) {
            error_log("Aucun utilisateur trouvé pour username: '{$username}'");
        } else {
            error_log("Hash stocké: " . $user->password);
        }
    
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $this->redirect('');
        } else {
            $error = "Identifiants invalides";

            $this->render('auth/login', [
                'pageTitle' => 'Connexion', 
                'error' => $error
            ]);
        }
    }
    
    public function logout(): void {
        session_destroy();
        $this->redirect('');
    }

    public function profile(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
            return;
        }
    
        $pageTitle = 'Mon profil';

        $this->render('auth/profile', [
            'pageTitle' => $pageTitle
        ]);
    }
    
}
