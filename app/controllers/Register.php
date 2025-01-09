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
            // Handle the form submission
            $member_data = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'membership_type_id' => intval($_POST['membership_type_id']),
                'photo' => $_FILES['photo'],
                'id_document' => $_FILES['id_document'],
                'payment_receipt' => $_FILES['payment_receipt']
            ];
    
            $result = $this->register_member($member_data);
    
            // Respond with JSON (success or error)
            if ($result) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Registration failed"]);
            }
            exit;
        }
        // Call the afficher_site method of the view to render the page
        $view = new RegistrationView();
        $view->afficher_site();
    }

    // Method to fetch all membership types
    public function get_membership_types() {
        return $this->membership_type_model->get_all();
    }

    // Method to handle member registration
    public function register_member($data) {
        // Create the member instance and insert member data
        $member_id = $this->member_model->insert_member($data);

        if (!$member_id) {
            return false; // Handle insertion error
        }

        // Process file uploads
        $upload_dir = './../public/uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create the directory if it doesn't exist
        }

        // Process the photo upload
        $photo_path = $this->upload_file($data['photo'], $upload_dir);
        if ($photo_path) {
            $this->member_model->update_photo($member_id, $photo_path);
        } else {
            return false; // Handle file upload error
        }

        // Process the ID document upload
        $id_doc_path = $this->upload_file($data['id_document'], $upload_dir);
        if ($id_doc_path) {
            $this->member_model->update_id_document($member_id, $id_doc_path);
        } else {
            return false; // Handle file upload error
        }

        // Process the payment receipt upload
        $payment_receipt_path = $this->upload_file($data['payment_receipt'], $upload_dir);
        if ($payment_receipt_path) {
            $this->member_model->update_payment_receipt($member_id, $payment_receipt_path);
        } else {
            return false; // Handle file upload error
        }

        return true; // Successfully registered the member
    }

    // Helper method to handle file uploads
    private function upload_file($file, $target_dir) {
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return '/uploads/' . basename($file['name']);
        }
        return false; // Return false if file upload fails
    }
}
