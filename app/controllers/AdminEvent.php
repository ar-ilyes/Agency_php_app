<?php

class AdminEvent {
    use Controller;
    
    private $eventModel;
    
    public function __construct() {
        $this->eventModel = new EventModel();
    }
    
    public function index() {
        // Parse the URL
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        
        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminEvent') {
                switch($urlParts[1]) {
                    case 'create':
                        $this->create();
                        return;
                    case 'delete':
                        $this->delete();
                        return;
                    case 'updateVolunteer':
                        $this->update_volunteer();
                        return;
                }
            }
        }
        
        // Get all events and their volunteers
        $events = $this->eventModel->get_all_events();
        foreach ($events as &$event) {
            $event['volunteers'] = $this->eventModel->get_event_volunteers($event['id']);
        }
        
        // Create view instance and pass data
        $view = new AdminEventView();
        $view->setData([
            'events' => $events
        ]);
        $view->setController($this);
        $view->index();
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminEvent');
            return;
        }
        
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'date_start' => $_POST['date_start'],
            'date_end' => $_POST['date_end'],
            'location' => $_POST['location'],
            'max_volunteers' => $_POST['max_volunteers']
        ];
        
        $event_id = $this->eventModel->create_event($data);
        
        if ($event_id) {
            header('Location: /adminEvent?success=created');
        } else {
            header('Location: /adminEvent?error=create_failed');
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminEvent');
            return;
        }
        
        $event_id = $_POST['event_id'];
        $success = $this->eventModel->delete_event($event_id);
        
        if ($success) {
            header('Location: /adminEvent?success=deleted');
        } else {
            header('Location: /adminEvent?error=delete_failed');
        }
    }
    
    public function update_volunteer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminEvent');
            return;
        }
        
        $volunteer_id = $_POST['volunteer_id'];
        $status = $_POST['status'];
        
        $success = $this->eventModel->update_volunteer_status($volunteer_id, $status);
        
        if ($success) {
            header('Location: /adminEvent?success=volunteer_updated');
        } else {
            header('Location: /adminEvent?error=volunteer_update_failed');
        }
    }
}