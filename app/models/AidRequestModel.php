<?php
class AidRequestModel {
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

    public function create_aid_request($data) {
        $c = $this->connect();
        if (!$c) return false;

        try {
            // Start transaction
            $c->beginTransaction();

            $query = "INSERT INTO aid_requests (first_name, last_name, birth_date, aid_type, description) 
                     VALUES (:first_name, :last_name, :birth_date, :aid_type, :description)";
            
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':birth_date' => $data['birth_date'],
                ':aid_type' => $data['aid_type'],
                ':description' => $data['description']
            ]);
            
            $request_id = $c->lastInsertId();
            
            // Commit transaction
            $c->commit();
            
            return $request_id;
        } catch(PDOException $ex) {
            // Rollback transaction on error
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
}