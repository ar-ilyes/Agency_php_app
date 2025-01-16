<?php

class News {
    use Controller;
    
    private $eventModel;
    private $announcementModel;
    
    public function __construct() {
        $this->eventModel = new EventModel();
        $this->announcementModel = new AnnouncementModel();
    }
    
    public function index() {
        // Get all news items
        $announcements = $this->announcementModel->get_all_announcements();
        $events = $this->eventModel->get_all_events();
        
        // Combine and sort by date
        $news = array_merge($announcements, $events);
        usort($news, function($a, $b) {
            $a_date = $a['created_at'] ?? $a['date_start'];
            $b_date = $b['created_at'] ?? $b['date_start'];
            return strtotime($b_date) - strtotime($a_date);
        });
        
        // Create and setup view
        $view = new NewsView();
        $view->setData([
            'news' => $news
        ]);
        $view->setController($this);
        $view->index();
    }
}