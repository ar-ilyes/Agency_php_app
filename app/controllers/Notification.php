<?php
class Notification {
    use Controller;

    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'member') {
            header('Location: /auth');
            return;
        }
        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $url = $_GET['url'] ?? '';
            $urlParts = explode('/', trim($url, '/'));
            
            if (count($urlParts) === 2 && $urlParts[1] === 'markAsRead') {
                $this->mark_as_read();
                return;
            }
        }

        $notifications = $this->notificationModel->get_member_notifications($member_id);

        $view = new NotificationView();
        $view->setData([
            'notifications' => $notifications
        ]);
        $view->setController($this);
        $view->index();
    }

    public function mark_as_read() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /notification');
            return;
        }

        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];
        $notification_id = $_POST['notification_id'];

        $success = $this->notificationModel->mark_as_read($notification_id, $member_id);

        if ($success) {
            header('Location: /notification?success=marked_read');
        } else {
            header('Location: /notification?error=mark_read_failed');
        }
    }
}
