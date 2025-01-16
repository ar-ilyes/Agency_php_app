<?php
class Partners {
    private $model;
    
    public function __construct() {
        $this->model = new PartnerModel(); 
    }
    
    public function index() {
        $category = $_GET['categorie'] ?? null;
        $city = $_GET['ville'] ?? null;
        
        $partners = $this->model->get_all_partners(); 
        $filtered_partners = $this->filterPartners($partners, $category, $city);
        $categories = $this->getUniqueCategories($partners);
        $cities = $this->getUniqueCities($partners);
        
        $view = new PartnersView();
        $view->afficher_site($filtered_partners, $categories, $cities, $category, $city);
    }
    
    private function filterPartners($partners, $category, $city) {
        return array_filter($partners, function($partner) use ($category, $city) {
            return (!$category || $partner['category'] === $category) && 
                    (!$city || $partner['city'] === $city); 
        });
    }
    
    private function getUniqueCategories($partners) {
        return array_unique(array_column($partners, 'category')); 
    }
    
    private function getUniqueCities($partners) {
        return array_unique(array_column($partners, 'city')); 
    }
    
    public function getPartnerDetails($id) {
        return $this->model->get_partner_by_id($id);
    }
}
