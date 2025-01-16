<?php

class AdminAidRequest {
    use Controller;
    
    private $aidRequestModel;
    private $aidTypesModel;
    
    public function __construct() {
        $this->aidRequestModel = new AidRequestModel();
        $this->aidTypesModel = new AidTypesModel();
    }
    
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            header('Location: /auth');
            return;
        }
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminAidRequest') {
                switch($urlParts[1]) {
                    case 'approve':
                        $this->approve();
                        return;
                }
            }
        }
        
        $filters = [
            'is_approved' => isset($_GET['is_approved']) ? (bool)$_GET['is_approved'] : null,
            'search' => $_GET['search'] ?? null,
            'aid_type' => $_GET['aid_type'] ?? null
        ];
        
        $aidRequests = $this->aidRequestModel->get_all_aid_requests($filters);
        $aidTypes = $this->aidTypesModel->get_all_aid_types();
        
        $stats = [
            'total_requests' => $this->aidRequestModel->get_total_requests(),
            'pending_requests' => $this->aidRequestModel->get_pending_requests(),
            'approved_requests' => $this->aidRequestModel->get_approved_requests(),
            'requests_by_type' => $this->aidRequestModel->get_requests_by_type()
        ];
        
        $view = new AdminAidRequestView();
        $view->setData([
            'aidRequests' => $aidRequests,
            'aidTypes' => $aidTypes,
            'filters' => $filters,
            'stats' => $stats
        ]);
        $view->setController($this);
        $view->index();
    }
    
    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminAidRequest');
            return;
        }
        
        $request_id = $_POST['request_id'];
        $success = $this->aidRequestModel->approve_request($request_id);
        
        if ($success) {
            header('Location: /adminAidRequest?success=approved');
        } else {
            header('Location: /adminAidRequest?error=approval_failed');
        }
    }
}
