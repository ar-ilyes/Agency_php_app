<?php
class AdminPartnerBenefits {
    use Controller;
    private $partnerModel;
    private $benefitsModel;
    private $membershipTypeModel;

    public function __construct() {
        $this->partnerModel = new PartnerModel();
        $this->benefitsModel = new BenefitsModel();
        $this->membershipTypeModel = new MembershipTypeModel();
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            header('Location: /auth');
            return;
        }
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        $partner_id = $urlParts[1] ?? null; // adminPartnerBenefits/:id

        if (!$partner_id) {
            header('Location: /adminPartner');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $urlParts[2] ?? ''; // adminPartnerBenefits/:id/:action
            
            switch ($action) {
                case 'createDiscount':
                    $this->create_standard_discount($partner_id);
                    break;
                case 'updateDiscount':
                    $this->update_standard_discount();
                    break;
                case 'deleteDiscount':
                    $this->delete_standard_discount();
                    break;
                case 'createOffer':
                    $this->create_special_offer($partner_id);
                    break;
                case 'updateOffer':
                    $this->update_special_offer();
                    break;
                case 'deleteOffer':
                    $this->delete_special_offer();
                    break;
                case 'createAdvantage':
                    $this->create_advantage($partner_id);
                    break;
                case 'updateAdvantage':
                    $this->update_advantage();
                    break;
                case 'deleteAdvantage':
                    $this->delete_advantage();
                    break;
            }
            
            header("Location: /adminPartnerBenefits/$partner_id");
            exit();
        }

        $partner = $this->partnerModel->get_partner_by_id($partner_id);
        $standardDiscounts = $this->benefitsModel->get_standard_discounts_of_partner($partner_id);
        $specialOffers = $this->benefitsModel->get_special_offers_of_partner($partner_id);
        $advantages = $this->benefitsModel->get_advantages_of_partner($partner_id);
        $membershipTypes = $this->membershipTypeModel->get_all();
        

        $view = new AdminPartnerBenefitsView();
        $view->setData([
            'partner' => $partner,
            'standardDiscounts' => $standardDiscounts,
            'specialOffers' => $specialOffers,
            'advantages' => $advantages,
            'membershipTypes' => $membershipTypes
        ]);
        $view->setController($this);
        $view->index();
    }

    private function create_standard_discount($partner_id) {
        $data = [
            'partner_id' => $partner_id,
            'description' => $_POST['description'],
            'discount_value' => $_POST['discount_value'],
            'discount_type' => $_POST['discount_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->create_standard_discount($data);
    }

    private function update_standard_discount() {
        $data = [
            'description' => $_POST['description'],
            'discount_value' => $_POST['discount_value'],
            'discount_type' => $_POST['discount_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->update_standard_discount($_POST['discount_id'], $data);
    }

    private function delete_standard_discount() {
        $this->benefitsModel->delete_standard_discount($_POST['discount_id']);
    }

    private function create_special_offer($partner_id) {
        $data = [
            'partner_id' => $partner_id,
            'description' => $_POST['description'],
            'discount_value' => $_POST['discount_value'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'offer_type' => $_POST['offer_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->create_special_offer($data);
    }

    private function update_special_offer() {
        $data = [
            'description' => $_POST['description'],
            'discount_value' => $_POST['discount_value'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'offer_type' => $_POST['offer_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->update_special_offer($_POST['offer_id'], $data);
    }

    private function delete_special_offer() {
        $this->benefitsModel->delete_special_offer($_POST['offer_id']);
    }

    private function create_advantage($partner_id) {
        $data = [
            'partner_id' => $partner_id,
            'description' => $_POST['description'],
            'advantage_type' => $_POST['advantage_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->create_advantage($data);
    }

    private function update_advantage() {
        $data = [
            'description' => $_POST['description'],
            'advantage_type' => $_POST['advantage_type'],
            'membership_types' => $_POST['membership_types'] ?? []
        ];
        
        $this->benefitsModel->update_advantage($_POST['advantage_id'], $data);
    }

    private function delete_advantage() {
        $this->benefitsModel->delete_advantage($_POST['advantage_id']);
    }
}
