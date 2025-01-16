<?php
class PartnerModel {
    private $dbname="association_db";
    private $host="127.0.0.1";
    private $port="3306";
    private $user="root";
    private $password="root";

    private function connect(){
        $dsn="mysql:dbname={$this->dbname}; host={$this->host}; port={$this->port}";

        try{
            $c=new PDO($dsn, $this->user, $this->password);
            return $c;
        }
        catch(PDOException $ex){
            printf("Database connection error", $ex->getMessage());
            exit();
        }
    }

    private function disconnect(&$c){
        $c=null;
    }

    private function query($c, $q){
        return $c->query($q);
    }

    public function get_all_partners() {
        $c = $this->connect();
        $q = "SELECT id, name, city, category , logo FROM PARTNER";
        $result = $this->query($c, $q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function get_partner_by_id($partner_id) {
        $c = $this->connect();
        $q = "SELECT id, name, city, category , logo FROM PARTNER WHERE id = '{$partner_id}'";
        $result = $this->query($c, $q)->fetch(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function get_partners_by_category($category) {
        $c = $this->connect();
        $q = "SELECT id, name, city, category FROM PARTNER WHERE category = '{$category}'";
        $result = $this->query($c, $q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function update_partner($partner_id, $data) {
        $c = $this->connect();
        $q = "UPDATE PARTNER SET 
              name = :name,
              city = :city,
              category = :category,
              logo = :logo
              WHERE id = :partner_id";
        
        $stmt = $c->prepare($q);
        $result = $stmt->execute([
            ':name' => $data['name'],
            ':city' => $data['city'],
            ':category' => $data['category'],
            ':logo' => $data['logo'],
            ':partner_id' => $partner_id
        ]);
        
        $this->disconnect($c);
        return $result;
    }
    
    public function update_logo($partner_id, $new_path) {
        $c = $this->connect();
        $q = "UPDATE PARTNER SET logo='{$new_path}' WHERE id='{$partner_id}'";
        $r = $this->query($c, $q);
        $this->disconnect($c);
        return $r;
    }

    public function delete_partner($partner_id) {
        $c = $this->connect();
        $q = "DELETE FROM PARTNER WHERE id = :partner_id";
        $stmt = $c->prepare($q);
        $result = $stmt->execute([':partner_id' => $partner_id]);
        $this->disconnect($c);
        return $result;
    }

    public function create_partner($data) {
        $c = $this->connect();
        
        try {
            // Start transaction since we're inserting into multiple tables
            $c->beginTransaction();
            
            // First create the user
            $userQuery = "INSERT INTO users (user_type, email, password) VALUES (:user_type, :email, :password)";
            $userStmt = $c->prepare($userQuery);
            $userResult = $userStmt->execute([
                ':user_type' => 'partner', // Assuming 'partner' is the user_type for partners
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT) // Always hash passwords
            ]);
            
            if (!$userResult) {
                throw new Exception("Failed to create user");
            }
            
            $userId = $c->lastInsertId();
            
            // Then create the partner with the user_id reference
            $partnerQuery = "INSERT INTO PARTNER (name, city, category, logo, user_id) 
                            VALUES (:name, :city, :category, :logo, :user_id)";
            $partnerStmt = $c->prepare($partnerQuery);
            $partnerResult = $partnerStmt->execute([
                ':name' => $data['name'],
                ':city' => $data['city'],
                ':category' => $data['category'],
                ':logo' => $data['logo'] ?? null,
                ':user_id' => $userId
            ]);
            
            if (!$partnerResult) {
                throw new Exception("Failed to create partner");
            }
            
            $partnerId = $c->lastInsertId();
            
            // If everything went well, commit the transaction
            $c->commit();
            
            return [
                'partner_id' => $partnerId,
                'user_id' => $userId
            ];
            
        } catch (Exception $e) {
            // If anything goes wrong, rollback the transaction
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function get_filtered_partners($filters = []) {
        $c = $this->connect();
        $q = "SELECT * FROM PARTNER WHERE 1=1";
        $params = [];

        if (!empty($filters['city'])) {
            $q .= " AND city = :city";
            $params[':city'] = $filters['city'];
        }

        if (!empty($filters['category'])) {
            $q .= " AND category = :category";
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $q .= " AND name LIKE :search";
            $params[':search'] = "%{$filters['search']}%";
        }

        $stmt = $c->prepare($q);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
}
