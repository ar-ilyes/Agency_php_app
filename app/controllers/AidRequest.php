<?php
class AidRequest {
    use Controller;
    private $model;

    public function __construct() {
        $this->model = new AidRequestModel();
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'member') {
            header('Location: /auth');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->submit_request();
            return;
        }
        $aid_types = $this->model->get_aid_types();
        $view = new AidRequestView();
        $view->index($aid_types);
    }

    public function get_aid_type_description($aid_type_id) {
        return $this->model->get_aid_type_description($aid_type_id);
    }

    public function submit_request() {
        $upload_dir = './../public/uploads/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $request_data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'birth_date' => $_POST['birth_date'],
            'aid_type' => $_POST['aid_type'],
            'description' => $_POST['description']
        ];

        $request_id = $this->model->create_aid_request($request_data);
        

        if (!$request_id) {
            echo json_encode(['success' => false, 'message' => 'Failed to create request']);
            return;
        }

        if (isset($_FILES['document'])) {
            error_log('Uploading document');
            $document_path = $this->upload_file($_FILES['document'], $upload_dir);
            if ($document_path) {
                $this->model->update_document($request_id, $document_path);
                echo json_encode(['success' => true, 'redirect' => '/aid-request/success']);
                return;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Failed to upload document']);
    }

    private function upload_file($file, $target_dir) {
        error_log('Uploading file with name :' . $file['name']);//to test bark
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return '/uploads/' . basename($file['name']);
        }
        return false; 
    }

    public function success() {
        $view = new AidRequestView();
        $view->success();
    }

    public function error() {
        $view = new AidRequestView();
        $view->error();
    }
}