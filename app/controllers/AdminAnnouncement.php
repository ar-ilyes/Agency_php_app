<?php

class AdminAnnouncement {
    use Controller;
    
    private $announcementModel;
    private $notificationModel;
    private $memberModel;
    
    public function __construct() {
        $this->announcementModel = new AnnouncementModel();
        $this->notificationModel = new NotificationModel();
        $this->memberModel = new MemberModel();
    }
    
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            header('Location: /auth');
            return;
        }
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminAnnouncement') {
                switch($urlParts[1]) {
                    case 'create':
                        $this->create();
                        return;
                    case 'update':
                        $this->update();
                        return;
                    case 'delete':
                        $this->delete();
                        return;
                }
            }
        }
        
        $announcements = $this->announcementModel->get_all_announcements();
        
        $view = new AdminAnnouncementView();
        $view->setData([
            'announcements' => $announcements
        ]);
        $view->setController($this);
        $view->index();
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminAnnouncement');
            return;
        }
        
        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $target_dir = 'uploads/announcements/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $image_path = $this->upload_file($_FILES['image'], $target_dir);
        }
        
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'image' => $image_path
        ];
        
        $announcement_id = $this->announcementModel->create_announcement($data);
        
        if ($announcement_id) {
            // Create notifications for all members
            $member_ids = $this->memberModel->get_all_member_ids();
            $notification_title = "New announcement: " . $data['title'];
            
            foreach ($member_ids as $member_id) {
                $this->notificationModel->create_notification(
                    $member_id,
                    $notification_title,
                    $data['description']
                );
            }
            
            header('Location: /adminAnnouncement?success=created');
        } else {
            header('Location: /adminAnnouncement?error=create_failed');
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminAnnouncement');
            return;
        }
        
        $announcement_id = $_POST['announcement_id'];
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'image' => $_POST['current_image']
        ];
        
        // Handle new image upload if provided
        if (!empty($_FILES['image']['name'])) {
            $target_dir = 'uploads/announcements/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $new_image_path = $this->upload_file($_FILES['image'], $target_dir);
            if ($new_image_path) {
                $data['image'] = $new_image_path;
            }
        }
        
        $success = $this->announcementModel->update_announcement($announcement_id, $data);
        
        if ($success) {
            header('Location: /adminAnnouncement?success=updated');
        } else {
            header('Location: /adminAnnouncement?error=update_failed');
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminAnnouncement');
            return;
        }
        
        $announcement_id = $_POST['announcement_id'];
        $success = $this->announcementModel->soft_delete_announcement($announcement_id);
        
        if ($success) {
            header('Location: /adminAnnouncement?success=deleted');
        } else {
            header('Location: /adminAnnouncement?error=delete_failed');
        }
    }
    
    private function upload_file($file, $target_dir) {
        $target_file = $target_dir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return '/uploads/announcements/' . basename($file['name']);
        }
        return false;
    }
}