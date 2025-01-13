<?php
class Benefits {
    private $benefits_model;
    private $member_model;
    
    public function __construct() {
        $this->benefits_model = new BenefitsModel();
        $this->member_model = new MemberModel();
    }
    
    public function index() {
        $member_id = $_GET['member_id'] ?? null;
        
        if (!$member_id) {
            header('Location: /error');
            exit;
        }
        
        $member_model = new MemberModel();
        $member = $member_model->get_member_by_id($member_id);
        $membership_type_id = $member['membership_type_id'];
        
        // Get filter and sort parameters
        $filters = [
            'category' => $_GET['category'] ?? null,
            'city' => $_GET['city'] ?? null
        ];
        $sort = $_GET['sort'] ?? null;
        
        // Get all benefits data with filters and sorting
        $data = $this->get_member_benefits($membership_type_id, $filters, $sort);
        
        // Add filter options to data
        $data['categories'] = $this->benefits_model->get_categories();
        $data['cities'] = $this->benefits_model->get_cities();
        $data['current_filters'] = $filters;
        $data['current_sort'] = $sort;
        
        $view = new BenefitsView();
        $view->afficher_site($data);
    }
    
    public function get_member_benefits($membership_type_id, $filters, $sort) {

        return [
            'membership_type' => $this->member_model->get_membership_type($membership_type_id),
            'standard_discounts' => $this->benefits_model->get_standard_discounts($membership_type_id, $filters, $sort),
            'special_offers' => $this->benefits_model->get_special_offers($membership_type_id, $filters, $sort),
            'advantages' => $this->benefits_model->get_advantages($membership_type_id, $filters, $sort)
        ];
    }
}
