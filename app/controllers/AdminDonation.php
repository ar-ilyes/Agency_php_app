<?php

class AdminDonation {
    use Controller;
    
    private $donationModel;
    
    public function __construct() {
        $this->donationModel = new DonationModel();
    }
    
    public function index() {
        // Parse the URL
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        
        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminDonation') {
                switch($urlParts[1]) {
                    case 'validate':
                        $this->validate();
                        return;
                }
            }
        }
        
        // Get filters from GET parameters
        $filters = [
            'is_validated' => isset($_GET['is_validated']) ? (bool)$_GET['is_validated'] : null,
            'search' => $_GET['search'] ?? null,
            'min_amount' => $_GET['min_amount'] ?? null,
            'max_amount' => $_GET['max_amount'] ?? null
        ];
        
        // Get donations with filters
        $donations = $this->donationModel->get_all_donations($filters);
        
        // Create view instance and pass data
        $view = new AdminDonationView();
        $view->setData([
            'donations' => $donations,
            'filters' => $filters
        ]);
        $view->setController($this);
        $view->index();
    }
    
    public function validate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminDonation');
            return;
        }
        
        $donation_id = $_POST['donation_id'];
        $success = $this->donationModel->validate_donation($donation_id);
        
        if ($success) {
            header('Location: /adminDonation?success=validated');
        } else {
            header('Location: /adminDonation?error=validation_failed');
        }
    }
}