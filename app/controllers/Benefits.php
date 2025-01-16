<?php
class Benefits {
    private $benefits_model;
    private $member_model;
    
    public function __construct() {
        $this->benefits_model = new BenefitsModel();
        $this->member_model = new MemberModel();
    }
    
    public function index() {
        error_log('Benefits controller index');
        if(isset($_SESSION['user'])){
            $user = $_SESSION['user'];
            $member_id = $user['entity_id'];
        }else{
            error_log('No user session');
            $member_id = null;
        }
        
        
        $member_model = new MemberModel();
        if($member_id){
            $member = $member_model->get_member_by_id($member_id);
            $membership_type_id = $member['membership_type_id'];
        }else{
            $membership_type_id = null;
        }
        error_log('Member ID: ' . $member_id);
        error_log('Membership type ID: ' . $membership_type_id);
        
        $filters = [
            'category' => $_GET['category'] ?? null,
            'city' => $_GET['city'] ?? null
        ];
        $sort = $_GET['sort'] ?? null;
        
        $data = $this->get_member_benefits($membership_type_id, $filters, $sort);
        
        $data['categories'] = $this->benefits_model->get_categories();
        $data['cities'] = $this->benefits_model->get_cities();
        $data['current_filters'] = $filters;
        $data['current_sort'] = $sort;

        error_log('Data :'.json_encode($data));
        
        $view = new BenefitsView();
        $view->afficher_site($data);
    }
    
    public function get_member_benefits($membership_type_id, $filters, $sort) {

        if(!$membership_type_id){
            return [
                'standard_discounts' => $this->benefits_model->get_standard_discounts($membership_type_id, $filters, $sort),
                'special_offers' => $this->benefits_model->get_special_offers($membership_type_id, $filters, $sort),
                'advantages' => $this->benefits_model->get_advantages($membership_type_id, $filters, $sort)
            ];
        }
        return [
            'membership_type' => $this->member_model->get_membership_type($membership_type_id),
            'standard_discounts' => $this->benefits_model->get_standard_discounts($membership_type_id, $filters, $sort),
            'special_offers' => $this->benefits_model->get_special_offers($membership_type_id, $filters, $sort),
            'advantages' => $this->benefits_model->get_advantages($membership_type_id, $filters, $sort)
        ];
    }
}
