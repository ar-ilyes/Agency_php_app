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
    if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
        header('Location: /auth');
        return;
    }
    $url = $_GET['url'] ?? '';
    $urlParts = explode('/', trim($url, '/'));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the URL matches the pattern /adminPartner/delete
        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'delete') {
            $this->delete();
            return;
        }

        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'create') {
            error_log('Creating partner');
            $this->create();
            return;
        }

        if (count($urlParts) === 2 && $urlParts[0] === 'adminPartner' && $urlParts[1] === 'update') {
            $this->update();
            return;
        }
    }

        $filters = [
            'city' => $_GET['city'] ?? null,
            'category' => $_GET['category'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $partners = $this->partnerModel->get_filtered_partners($filters);
        
        $categories = $this->benefitsModel->get_categories();
        $cities = $this->benefitsModel->get_cities();

        $partner_stats = [];
        foreach ($partners as $partner) {
            $partner_stats[$partner['id']] = $this->benefitsModel->get_partner_benefits_stats($partner['id']);
        }


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
            header('Location: /adminPartner');
            return;
        }

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
            header('Location: /adminPartner?success=created');
        } else {
            header('Location: /adminPartner?error=create_failed');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminPartner');
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
            header('Location: /adminPartner?success=updated');
        } else {
            header('Location: /adminPartner?error=update_failed');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminPartner');
            return;
        }

        $partner_id = $_POST['partner_id'];
        $success = $this->partnerModel->delete_partner($partner_id);

        if ($success) {
            header('Location: /adminPartner?success=deleted');
        } else {
            header('Location: /adminPartner?error=delete_failed');
        }
    }
}
