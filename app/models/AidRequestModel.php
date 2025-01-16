<?php
class AidRequestModel {
    private $dbname = "association_db";
    private $host = "127.0.0.1";
    private $port = "3306";
    private $user = "root";
    private $password = "";

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

    public function create_aid_request($data) {
        $c = $this->connect();
        if (!$c) return false;
    
        try {
            $c->beginTransaction();
    
            $member_id = null;
            if (isset($_SESSION['user']) && isset($_SESSION['user']['entity_id'])) {
                $member_id = $_SESSION['user']['entity_id'];
            }
    
            $query = "INSERT INTO aid_requests (first_name, last_name, birth_date, aid_type, description, made_by) 
                     VALUES (:first_name, :last_name, :birth_date, :aid_type, :description, :made_by)";
            
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':birth_date' => $data['birth_date'],
                ':aid_type' => $data['aid_type'],
                ':description' => $data['description'],
                ':made_by' => $member_id
            ]);
            
            $request_id = $c->lastInsertId();
            
            $c->commit();
            
            return $request_id;
        } catch(PDOException $ex) {
            if ($c->inTransaction()) {
                $c->rollBack();
            }
            error_log("Error creating aid request: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function update_document($request_id, $document_path) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "UPDATE aid_requests SET document_path = :document_path WHERE id = :id";
            $stmt = $c->prepare($query);
            $result = $stmt->execute([
                ':document_path' => $document_path,
                ':id' => $request_id
            ]);
            
            return $result;
        } catch(PDOException $ex) {
            error_log("Error updating document path: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_aid_request($request_id) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT * FROM aid_requests WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $request_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching aid request: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_aid_types() {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT * FROM aid_types ORDER BY name";
            $stmt = $c->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching aid types: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_aid_type_description($aid_type_id) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT description FROM aid_types WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $aid_type_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['description'] : '';
        } catch(PDOException $ex) {
            error_log("Error fetching aid type description: " . $ex->getMessage());
            return '';
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_member_requests($member_id) {
        $c = $this->connect();
        if (!$c) return false;
    
        try {
            $query = "SELECT *, 'aid_request' as type 
                     FROM aid_requests 
                     WHERE made_by = :member_id
                     ORDER BY created_at DESC";
            $stmt = $c->prepare($query);
            $stmt->execute([':member_id' => $member_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching member aid requests: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function get_all_aid_requests($filters = []) {
        $c = $this->connect();
        if (!$c) return false;
    
        try {
            $query = "SELECT ar.*, at.name as aid_type_name 
                     FROM aid_requests ar
                     JOIN aid_types at ON ar.aid_type = at.id
                     WHERE 1=1";
            $params = [];
    
            if (isset($filters['is_approved'])) {
                $query .= " AND ar.is_approved = :is_approved";
                $params[':is_approved'] = $filters['is_approved'];
            }
    
            if (!empty($filters['aid_type'])) {
                $query .= " AND ar.aid_type = :aid_type";
                $params[':aid_type'] = $filters['aid_type'];
            }
    
            if (!empty($filters['search'])) {
                $query .= " AND (ar.first_name LIKE :search OR ar.last_name LIKE :search)";
                $params[':search'] = "%{$filters['search']}%";
            }
    
            $query .= " ORDER BY ar.created_at DESC";
    
            $stmt = $c->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error fetching aid requests: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function approve_request($request_id) {
        $c = $this->connect();
        if (!$c) return false;
    
        try {
            $query = "UPDATE aid_requests SET is_approved = TRUE WHERE id = :id";
            $stmt = $c->prepare($query);
            return $stmt->execute([':id' => $request_id]);
        } catch(PDOException $ex) {
            error_log("Error approving aid request: " . $ex->getMessage());
            return false;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function get_total_requests() {
        $c = $this->connect();
        if (!$c) return 0;
    
        try {
            $query = "SELECT COUNT(*) as total FROM aid_requests";
            $stmt = $c->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $ex) {
            error_log("Error getting total requests: " . $ex->getMessage());
            return 0;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function get_pending_requests() {
        $c = $this->connect();
        if (!$c) return 0;
    
        try {
            $query = "SELECT COUNT(*) as total FROM aid_requests WHERE is_approved = FALSE";
            $stmt = $c->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $ex) {
            error_log("Error getting pending requests: " . $ex->getMessage());
            return 0;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function get_approved_requests() {
        $c = $this->connect();
        if (!$c) return 0;
    
        try {
            $query = "SELECT COUNT(*) as total FROM aid_requests WHERE is_approved = TRUE";
            $stmt = $c->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $ex) {
            error_log("Error getting approved requests: " . $ex->getMessage());
            return 0;
        } finally {
            $this->disconnect($c);
        }
    }
    
    public function get_requests_by_type() {
        $c = $this->connect();
        if (!$c) return [];
    
        try {
            $query = "SELECT 
                        at.name,
                        COUNT(*) as total,
                        SUM(CASE WHEN ar.is_approved THEN 1 ELSE 0 END) as approved,
                        SUM(CASE WHEN NOT ar.is_approved THEN 1 ELSE 0 END) as pending
                     FROM aid_requests ar
                     JOIN aid_types at ON ar.aid_type = at.id
                     GROUP BY at.id, at.name
                     ORDER BY at.name";
            $stmt = $c->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            error_log("Error getting requests by type: " . $ex->getMessage());
            return [];
        } finally {
            $this->disconnect($c);
        }
    }
    
}