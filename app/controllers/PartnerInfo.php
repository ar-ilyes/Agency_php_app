<?php
class PartnerInfo {
    use Controller;
    private $partnerModel;

    public function __construct() {
        $this->partnerModel = new PartnerModel();
    }

    public function index() {
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        
        if (count($urlParts) < 2) {
            header('Location: /404');
            return;
        }

        $partner_id = $urlParts[1];
        
        $partner = $this->partnerModel->get_partner_by_id($partner_id);
        
        if (!$partner) {
            header('Location: /404');
            return;
        }

        $view = new PartnerInfoView();
        $view->setData([
            'partner' => $partner
        ]);
        $view->setController($this);
        $view->index();
    }
}
