<?php
class PartnerSelect {
    use Controller;
    private $memberModel;
    private $partnerModel;

    public function __construct() {
        $this->memberModel = new MemberModel();
        $this->partnerModel = new PartnerModel();
    }

    public function index() {
        $member_id = 1; 
        $allPartners = $this->partnerModel->get_all_partners();
        $currentFavorites = $this->memberModel->get_member_favorites($member_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected_partners = $_POST['selected_partners'] ?? [];
            $this->save_favorites($selected_partners);
            return;
        }
        
        $view = new PartnerSelectView();
        $view->setData([
            'partners' => $allPartners,
            'currentFavorites' => $currentFavorites
        ]);
        
        $view->index();
    }

    public function save_favorites() {
            $user = $_SESSION['user'];
            $member_id = $user['entity_id'];
           
            $data = json_decode(file_get_contents('php://input'), true);
            $selectedPartners = $data['selected_partners'] ?? [];
            
            if (empty($selectedPartners)) {
                echo json_encode(['success' => false, 'message' => 'No partners selected']);
                return;
            }
        
            foreach ($selectedPartners as $partner_id) {
                $this->memberModel->add_favorite($member_id, $partner_id);
            }
            error_log('Favorites saved successfully' . json_encode($selectedPartners));
            $success = true;
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Favorites saved successfully' : 'Error saving favorites'
            ]);
    }
}
