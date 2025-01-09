<?php
class Partners {
    private $model;
    private $view;
    
    public function __construct() {
        
        $this->model = new PartnersModel();
    }
    
    public function index() {
        $categorie = $_GET['categorie'] ?? null;
        $ville = $_GET['ville'] ?? null;
        
        $partners = $this->model->getAllPartners();
        $filtered_partners = $this->filterPartners($partners, $categorie, $ville);
        $categories = $this->getUniqueCategories($partners);
        $cities = $this->getUniqueCities($partners);
        $view = new PartnersView();
        $view->afficher_site($filtered_partners, $categories, $cities, $categorie, $ville);
    }
    
    private function filterPartners($partners, $categorie, $ville) {
        return array_filter($partners, function($partner) use ($categorie, $ville) {
            return (!$categorie || $partner['categorie'] === $categorie) &&
                    (!$ville || $partner['ville'] === $ville);
        });
    }
    
    private function getUniqueCategories($partners) {
        return array_unique(array_column($partners, 'categorie'));
    }
    
    private function getUniqueCities($partners) {
        return array_unique(array_column($partners, 'ville'));
    }
    
    public function getPartnerDetails($id) {
        return $this->model->getPartnerById($id);
    }
}
