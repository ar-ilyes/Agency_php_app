<?php
class Partners {
    private $model;
    
    public function __construct() {
        $this->model = new PartnerModel(); // Fixed class name
    }
    
    public function index() {
        $category = $_GET['categorie'] ?? null;
        $city = $_GET['ville'] ?? null;
        
        $partners = $this->model->get_all_partners(); // Using the correct method name
        $filtered_partners = $this->filterPartners($partners, $category, $city);
        $categories = $this->getUniqueCategories($partners);
        $cities = $this->getUniqueCities($partners);
        
        $view = new PartnersView();
        $view->afficher_site($filtered_partners, $categories, $cities, $category, $city);
    }
    
    private function filterPartners($partners, $category, $city) {
        return array_filter($partners, function($partner) use ($category, $city) {
            return (!$category || $partner['category'] === $category) && // Changed to match DB column
                   (!$city || $partner['city'] === $city); // Changed to match DB column
        });
    }
    
    private function getUniqueCategories($partners) {
        return array_unique(array_column($partners, 'category')); // Changed to match DB column
    }
    
    private function getUniqueCities($partners) {
        return array_unique(array_column($partners, 'city')); // Changed to match DB column
    }
    
    public function getPartnerDetails($id) {
        return $this->model->get_partner_by_id($id);
    }
}
