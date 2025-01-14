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
        // get $partner_id as param id
        $partner_id = $_GET['id'] ?? null;
        $partner = $this->partnerModel->get_partner_by_id($partner_id);
        $standardDiscounts = $this->benefitsModel->get_standard_discounts(null, ['partner_id' => $partner_id]);
        $specialOffers = $this->benefitsModel->get_special_offers(null, ['partner_id' => $partner_id]);
        $advantages = $this->benefitsModel->get_advantages(null, ['partner_id' => $partner_id]);
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

}
