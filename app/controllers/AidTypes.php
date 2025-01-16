<?php
class AidTypes {
    private $aid_types_model;
    
    public function __construct() {
        $this->aid_types_model = new AidTypesModel();
    }
    
    public function index() {
        
        $data = $this->get_aid_types_data();
        $view = new AidTypesView();
        $view->afficher_site($data);
    }
    
    public function get_aid_types_data() {
        return [
            'aid_types' => $this->aid_types_model->get_all_aid_types()
        ];
    }
}
