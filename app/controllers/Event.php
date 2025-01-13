<?php
class Event {
    private $event_model;
    
    public function __construct() {
        $this->event_model = new EventModel();
    }
    
    public function index() {
        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->register_volunteer();
            echo json_encode($result);
            exit;
        }
        if (!$member_id) {
            header('Location: /error');
            exit;
        }
        
        $events = $this->event_model->get_all_events();
        $member_events = $this->event_model->get_member_events($member_id);
        
        $view = new EventView();
        $view->afficher_site([
            'member_id' => $member_id,
            'events' => $events,
            'member_events' => $member_events
        ]);
    }
    
    public function register_volunteer() {
        
        $event_id = $_POST['event_id'] ?? null;
        $member_id = $_POST['member_id'] ?? null;
        
        if (!$event_id || !$member_id) {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            exit;
        }
        
        $result = $this->event_model->register_volunteer($event_id, $member_id);
        echo json_encode($result);
        exit;
    }
}
