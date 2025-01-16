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
            echo json_encode($user);
            
            // if ($user && password_verify($password, $user['password'])) { //TODO: Uncomment this line to enable password hashing
            if ($user && $password === $user['password']) {//keeping this for testing bark

                $_SESSION['user'] = [
                    'user_id' => $user['id'], // User table ID
                    'entity_id' => $user['entity_id'], // Member, Partner, or Admin ID
                    'type' => $user['user_type'],
                    'email' => $user['email']
                ];
                error_log('User logged in: ' . json_encode($_SESSION['user']));
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
                header('Location: /member');
                break;
            case 'partner':
                header('Location: /partner');
                break;
            case 'admin':
                header('Location: /adminPartner');
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
