<?php 
class Home {
    use Controller;
    private $eventModel;
    private $announcementModel;
    private $benefitsModel;
    private $membershipTypeModel;
    private $partnerModel;

    public function __construct() {
        $this->eventModel = new EventModel();
        $this->announcementModel = new AnnouncementModel();
        $this->benefitsModel = new BenefitsModel();
        $this->membershipTypeModel = new MembershipTypeModel();
        $this->partnerModel = new PartnerModel();
    }

    public function index() {
        $latest_news = $this->get_latest_news();
        
        $benefits = $this->get_membership_benefits();

        $partners = $this->get_latest_partners();
        
        $view = new HomeView();
        $view->setData([
            'latest_news' => $latest_news,
            'benefits' => $benefits,
            'partners' => $partners
        ]);
        $view->index();
    }


    /////////////////////
    public function get_latest_news(): array {
        $announcements = $this->announcementModel->get_latest_announcements(4);
        
        $events = $this->eventModel->get_latest_events(4);
        
        $news = array_merge($announcements, $events);
        usort($news, function($a, $b) {
            $a_date = $a['created_at'] ?? $a['date_start'];
            $b_date = $b['created_at'] ?? $b['date_start'];
            return strtotime($b_date) - strtotime($a_date);
        });
        
        return array_slice($news, 0, 4);
    }

    public function get_membership_benefits(): array {
        $membership_types = $this->membershipTypeModel->get_all();
        $benefits = [];
        
        foreach ($membership_types as $type) {
            $type_benefits = [
                'membership_type' => $type,
                'advantages' => $this->benefitsModel->get_latest_advantages($type['membership_type_id'], 3),
                'card_color' => $this->get_card_color($type['name'])
            ];
            $benefits[] = $type_benefits;
        }
        
        return $benefits;
    }

    private function get_card_color($membership_type): string {
        return match(strtolower($membership_type)) {
            'basic' => 'bg-gray-200',
            'silver' => 'bg-gray-400',
            'gold' => 'bg-yellow-400',
            'platinum' => 'bg-gray-800 text-white',
            default => 'bg-blue-200'
        };
    }
    private function get_latest_partners(): array {
        $partners = $this->partnerModel->get_all_partners();
        return array_slice($partners, 0, 5);
    }
}
