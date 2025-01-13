<?php
class Auth {
    private $user_model;
    
    public function __construct() {
        $this->user_model = new UserModel();
        session_start();
    }
    
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->user_model->verify_user($email);
            
            // if ($user && password_verify($password, $user['password'])) { TODO: Uncomment this line to enable password hashing
            if ($user && $password === $user['password']) {

                // Store necessary user information in session
                $_SESSION['user'] = [
                    'user_id' => $user['id'], // User table ID
                    'entity_id' => $user['entity_id'], // Member, Partner, or Admin ID
                    'type' => $user['user_type'],
                    'email' => $user['email']
                ];
                
                // Redirect based on user type
                $this->redirect_user($user['user_type']);
            } else {
                $view = new AuthView();
                $view->afficher_login(['error' => 'Invalid email or password']);
            }
        } else {
            $view = new AuthView();
            $view->afficher_login();
        }
    }
    
    private function redirect_user($user_type) {
        switch ($user_type) {
            case 'member':
                header('Location: /member/dashboard');
                break;
            case 'partner':
                header('Location: /partner/dashboard');
                break;
            case 'admin':
                header('Location: /admin/dashboard');
                break;
            default:
                header('Location: /auth/login');
                break;
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
    
    public static function check_auth() {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }
        return $_SESSION['user'];
    }
}
