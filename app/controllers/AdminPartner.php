<?php
class AdminPartner {
    use Controller;
    private $partnerModel;
    private $benefitsModel;

    public function __construct() {
        $this->partnerModel = new PartnerModel();
        $this->benefitsModel = new BenefitsModel();
    }

    public function index() {

        // Parse the URL
    $url = $_GET['url'] ?? '';
    $urlParts = explode('/', trim($url, '/'));

    // Check if the request is a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the URL matches the pattern /adminPartner/delete
        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'delete') {
            $this->delete();
            return;
        }

        // Check if the URL matches the pattern /adminPartner/create
        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'create') {
            error_log('Creating partner');
            $this->create();
            return;
        }

        // Check if the URL matches the pattern /adminPartner/update
        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'update') {
            $this->update();
            return;
        }
    }

        // Get filters from GET parameters
        $filters = [
            'city' => $_GET['city'] ?? null,
            'category' => $_GET['category'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        // Get partners with filters
        $partners = $this->partnerModel->get_filtered_partners($filters);
        
        // Get categories and cities for filters
        $categories = $this->benefitsModel->get_categories();
        $cities = $this->benefitsModel->get_cities();

        // Get statistics for each partner
        $partner_stats = [];
        foreach ($partners as $partner) {
            $partner_stats[$partner['id']] = $this->benefitsModel->get_partner_benefits_stats($partner['id']);
        }


        // Create view instance and pass data
        $view = new AdminPartnerView();
        $view->setData([
            'partners' => $partners,
            'categories' => $categories,
            'cities' => $cities,
            'filters' => $filters,
            'partner_stats' => $partner_stats
        ]);
        $view->setController($this);
        $view->index();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/partner');
            return;
        }

        // Handle file upload for logo
        $logo = $_FILES['logo'] ?? null;
        $logo_path = null;
        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/logos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $logo_path = $upload_dir . uniqid() . '_' . basename($logo['name']);
            move_uploaded_file($logo['tmp_name'], $logo_path);
        }

        $data = [
            'name' => $_POST['name'],
            'city' => $_POST['city'],
            'category' => $_POST['category'],
            'logo' => $logo_path,
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        $partner_id = $this->partnerModel->create_partner($data);

        if ($partner_id) {
            header('Location: /admin/partner?success=created');
        } else {
            header('Location: /admin/partner?error=create_failed');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/partner');
            return;
        }

        $partner_id = $_POST['partner_id'];
        $data = [
            'name' => $_POST['name'],
            'city' => $_POST['city'],
            'category' => $_POST['category']
        ];

        // Handle logo upload if provided
        if (!empty($_FILES['logo']['name'])) {
            $logo = $_FILES['logo'];
            if ($logo['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/logos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $data['logo'] = $upload_dir . uniqid() . '_' . basename($logo['name']);
                move_uploaded_file($logo['tmp_name'], $data['logo']);
            }
        } else {
            $data['logo'] = $_POST['current_logo'];
        }

        $success = $this->partnerModel->update_partner($partner_id, $data);

        if ($success) {
            header('Location: /admin/partner?success=updated');
        } else {
            header('Location: /admin/partner?error=update_failed');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/partner');
            return;
        }

        $partner_id = $_POST['partner_id'];
        $success = $this->partnerModel->delete_partner($partner_id);

        if ($success) {
            header('Location: /admin/partner?success=deleted');
        } else {
            header('Location: /admin/partner?error=delete_failed');
        }
    }
}
