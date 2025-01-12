<?php
class Benefits {
    private $benefits_model;
    
    public function __construct() {
        $this->benefits_model = new BenefitsModel();
    }
    
    public function index() {
        // This should be retrieved from the session or passed as parameter
        $member_id = $_GET['member_id'] ?? null;
        
        if (!$member_id) {
            // Handle error - redirect or show message
            header('Location: /error');
            exit;
        }
        
        // Get member's membership type (you should have this in your MemberModel)
        $member_model = new MemberModel();
        $member = $member_model->get_member_by_id($member_id);
        $membership_type_id = $member['membership_type_id'];
        
        // Get all benefits data
        $data = $this->get_member_benefits($membership_type_id);
        
        // Create and show the view
        $view = new BenefitsView();
        $view->afficher_site($data);
    }
    
    public function get_member_benefits($membership_type_id) {
        return [
            'membership_type' => $this->benefits_model->get_membership_type($membership_type_id),
            'standard_discounts' => $this->benefits_model->get_standard_discounts($membership_type_id),
            'special_offers' => $this->benefits_model->get_special_offers($membership_type_id),
            'advantages' => $this->benefits_model->get_advantages($membership_type_id)
        ];
    }
}
