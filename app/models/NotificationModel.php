<?php
class NotificationModel {
    private $dbname = "association_db";
    private $host = "127.0.0.1";
    private $port = "3306";
    private $user = "root";
    private $password = "root";

    private function connect() {
        $dsn = "mysql:dbname={$this->dbname}; host={$this->host}; port={$this->port}";
        try {
            $c = new PDO($dsn, $this->user, $this->password);
            $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $c;
        } catch(PDOException $ex) {
            error_log("Database connection error: " . $ex->getMessage());
            return false;
        }
    }

    private function disconnect(&$c) {
        $c = null;
    }

    public function get_member_notifications($member_id) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT * FROM notifications 
                     WHERE member_id = :member_id 
                     ORDER BY created_at DESC";
            $stmt = $c->prepare($query);
            $stmt->execute([':member_id' => $member_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching notifications: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function create_notification($member_id, $title, $description) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "INSERT INTO notifications (member_id, title, description) 
                     VALUES (:member_id, :title, :description)";
            $stmt = $c->prepare($query);
            $result = $stmt->execute([
                ':member_id' => $member_id,
                ':title' => $title,
                ':description' => $description
            ]);
            return $result ? $c->lastInsertId() : false;
        } catch(PDOException $ex) {
            error_log("Error creating notification: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function mark_as_read($notification_id, $member_id) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "UPDATE notifications 
                     SET is_read = TRUE 
                     WHERE id = :id AND member_id = :member_id";
            $stmt = $c->prepare($query);
            return $stmt->execute([
                ':id' => $notification_id,
                ':member_id' => $member_id
            ]);
        } catch(PDOException $ex) {
            error_log("Error marking notification as read: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_unread_count($member_id) {
        $c = $this->connect();
        if (!$c) return 0;

        try {
            $query = "SELECT COUNT(*) FROM notifications 
                     WHERE member_id = :member_id AND is_read = FALSE";
            $stmt = $c->prepare($query);
            $stmt->execute([':member_id' => $member_id]);
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            error_log("Error counting unread notifications: " . $ex->getMessage());
            return 0;
        } finally {
            $this->disconnect($c);
        }
    }
}
