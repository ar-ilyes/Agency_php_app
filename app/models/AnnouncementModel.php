<?php

class AnnouncementModel {
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

    public function get_all_announcements() {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT * FROM announcements 
                     WHERE deleted_at IS NULL 
                     ORDER BY created_at DESC";
            $stmt = $c->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching announcements: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function create_announcement($data) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "INSERT INTO announcements (title, description, start_date, end_date, image) 
                     VALUES (:title, :description, :start_date, :end_date, :image)";
            $stmt = $c->prepare($query);
            $result = $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date'],
                ':image' => $data['image']
            ]);
            return $result ? $c->lastInsertId() : false;
        } catch(PDOException $ex) {
            error_log("Error creating announcement: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function update_announcement($id, $data) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "UPDATE announcements 
                     SET title = :title, 
                         description = :description, 
                         start_date = :start_date, 
                         end_date = :end_date" .
                     ($data['image'] ? ", image = :image" : "") .
                     " WHERE id = :id AND deleted_at IS NULL";
            
            $params = [
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date'],
                ':id' => $id
            ];

            if ($data['image']) {
                $params[':image'] = $data['image'];
            }

            $stmt = $c->prepare($query);
            return $stmt->execute($params);
        } catch(PDOException $ex) {
            error_log("Error updating announcement: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function soft_delete_announcement($id) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "UPDATE announcements 
                     SET deleted_at = CURRENT_TIMESTAMP 
                     WHERE id = :id";
            $stmt = $c->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch(PDOException $ex) {
            error_log("Error soft deleting announcement: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_latest_announcements($limit = 4) {
        $c = $this->connect();
        $query = "SELECT *, 'announcement' as type FROM announcements 
                WHERE deleted_at IS NULL 
                ORDER BY created_at DESC 
                LIMIT $limit";
        
        $stmt = $c->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
    

}