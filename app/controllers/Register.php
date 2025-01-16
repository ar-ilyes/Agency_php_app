<?php

class Register {

    private $member_model;
    private $membership_type_model;

    public function __construct() {
        $this->member_model = new MemberModel();
        $this->membership_type_model = new MembershipTypeModel();
    }

    public function index() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $member_data = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'membership_type_id' => intval($_POST['membership_type_id']),
                'photo' => $_FILES['photo'],
                'id_document' => $_FILES['id_document'],
                'payment_receipt' => $_FILES['payment_receipt']
            ];
    
            $result = $this->register_member($member_data);
    
            if ($result) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Registration failed"]);
            }
            exit;
        }
        
        $view = new RegistrationView();
        $view->afficher_site();
    }

    public function get_membership_types() {
        return $this->membership_type_model->get_all();
    }

    public function register_member($data) {
        $member_id = $this->member_model->insert_member($data);

        if (!$member_id) {
            return false; 
        }

        $upload_dir = './../public/uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); 
        }

        $photo_path = $this->upload_file($data['photo'], $upload_dir);
        if ($photo_path) {
            $this->member_model->update_photo($member_id, $photo_path);
        } else {
            return false; 
        }

        $id_doc = $this->upload_file($data['id_document'], $upload_dir);
        if ($id_doc) {
            $this->member_model->update_id_document($member_id, $id_doc);
        } else {
            return false; 
        }

        $payment_receipt = $this->upload_file($data['payment_receipt'], $upload_dir);
        if ($payment_receipt) {
            $this->member_model->update_payment_receipt($member_id, $payment_receipt);
        } else {
            return false; 
        }
        $this->generate_card($member_id);


        return true; 
    }

    private function upload_file($file, $target_dir) {
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return '/uploads/' . basename($file['name']);
        }
        return false; 
    }

    public function get_member_data($member_id) {
        return $this->member_model->get_member_by_id($member_id);
    }

    public function generate_card($member_id) {
        $member_data = $this->get_member_data($member_id);
        
        if (!$member_data) {
            return json_encode(['error' => 'Member not found']);
        }
        
        $view = new CardView($this);
        $card_path = $view->generate_card_image($member_id);
        
        return json_encode([
            'success' => true,
            'card_path' => $card_path
        ]);
    }
    
    
}
