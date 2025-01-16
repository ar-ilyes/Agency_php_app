<?php
class Donation {
    private $donation_model;
    
    public function __construct() {
        $this->donation_model = new DonationModel();
    }
    
    public function index() {
        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];
        
        if (!$member_id) {
            header('Location: /error');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->process_donation($member_id);
            if ($result) {
                header('Location: /member?success=1');
                exit;
            }
        }
        
        $view = new DonationView();
        $view->afficher_site(['member_id' => $member_id]);
    }
    
    private function process_donation($member_id) {
        if (!isset($_FILES['payment_receipt']) || !isset($_POST['amount'])) {
            return false;
        }
        
        $upload_dir = './../public/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $payment_receipt = $this->upload_file($_FILES['payment_receipt'], $upload_dir);
        if (!$payment_receipt) {
            return false;
        }
        
        return $this->donation_model->create_donation(
            $member_id,
            floatval($_POST['amount']),
            $payment_receipt
        );
    }
    
    private function upload_file($file, $target_dir) {
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return '/uploads/' . basename($file['name']);
        }
        return false;
    }
}
