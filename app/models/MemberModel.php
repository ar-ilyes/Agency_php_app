<?php
class MemberModel {
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

    public function insert_member($data) {
        $c = $this->connect();
        
        try {
            // Start transaction
            $c->beginTransaction();
            
            // First, create the user
            // $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT); TODO: uncomment this line to hash password after
            $hashedPassword = $data['password'];
            $stmt = $c->prepare("INSERT INTO users (user_type, email, password) VALUES ('member', :email, :password)");
            $stmt->execute([
                ':email' => $data['email'],
                ':password' => $hashedPassword
            ]);
            $userId = $c->lastInsertId();
            
            // Then, create the member with the user_id
            $stmt = $c->prepare("INSERT INTO members (first_name, last_name, email, address, city, membership_type_id, user_id,is_approved,inscription_date) 
                                VALUES (:first_name, :last_name, :email, :address, :city, :membership_type_id, :user_id, false, :inscription_date)");
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':email' => $data['email'],
                ':address' => $data['address'],
                ':city' => $data['city'],
                ':membership_type_id' => $data['membership_type_id'],
                ':user_id' => $userId,
                ':inscription_date' => $data['inscription_date'] ?? date('Y-m-d H:i:s')
            ]);
            $memberId = $c->lastInsertId();
            
            // Commit transaction
            $c->commit();
            
            $this->disconnect($c);
            return $memberId;
            
        } catch (PDOException $e) {
            // Rollback transaction on error
            $c->rollBack();
            $this->disconnect($c);
            throw $e;
        }
    }

    public function update_photo($member_id, $new_path){
        $c=$this->connect();
        $q="UPDATE members SET photo='{$new_path}' WHERE member_id='{$member_id}'";
        $r=$this->query($c, $q);
        $this->disconnect($c);
        return $r;
    }

    public function update_id_document($member_id, $new_path){
        $c=$this->connect();
        $q="UPDATE members SET id_document='{$new_path}' WHERE member_id='{$member_id}'";
        $r=$this->query($c, $q);
        $this->disconnect($c);
        return $r;
    }

    public function update_payment_receipt($member_id, $new_path){
        $c=$this->connect();
        $q="UPDATE members SET payment_receipt='{$new_path}' WHERE member_id='{$member_id}'";
        $r=$this->query($c, $q);
        $this->disconnect($c);
        return $r;
    }

    public function get_member_by_id($member_id) {
        $c = $this->connect();
        $q = "SELECT * FROM members WHERE member_id = '{$member_id}'";
        $result = $this->query($c, $q)->fetch(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function get_membership_type($membership_type_id) {
        $c = $this->connect();
        $q = "SELECT * FROM membership_types WHERE membership_type_id = '{$membership_type_id}'";
        $result = $this->query($c, $q)->fetch(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
    
    public function get_member_favorites($member_id) {
        $c = $this->connect();
        $q = "SELECT p.* FROM favorites f 
              JOIN PARTNER p ON f.partner_id = p.id 
              WHERE f.member_id = '{$member_id}'";
        $result = $this->query($c, $q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function add_favorite($member_id, $partner_id) {
        $c = $this->connect();
        $q = "INSERT IGNORE INTO favorites (member_id, partner_id) 
              VALUES ('{$member_id}', '{$partner_id}')";
        $result = $this->query($c, $q);
        $this->disconnect($c);
        return $result;
    }

    public function remove_favorite($member_id, $partner_id) {
        $c = $this->connect();
        $q = "DELETE FROM favorites 
              WHERE member_id = '{$member_id}' 
              AND partner_id = '{$partner_id}'";
        $result = $this->query($c, $q);
        $this->disconnect($c);
        return $result;
    }

    public function update_member($member_id, $data) {
        $c = $this->connect();
        $q = "UPDATE members SET 
              first_name = :first_name,
              last_name = :last_name,
              email = :email,
              address = :address,
              city = :city,
              photo = :photo
              WHERE member_id = :member_id";
        
        $stmt = $c->prepare($q);
        $result = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':city' => $data['city'],
            ':photo' => $data['photo'],
            ':member_id' => $member_id
        ]);
        
        $this->disconnect($c);
        return $result;
    }
    

    public function get_filtered_members($filters = []) {
        $c = $this->connect();
        $query = "SELECT m.*, mt.name as membership_type_name 
                  FROM members m 
                  JOIN membership_types mt ON m.membership_type_id = mt.membership_type_id 
                  WHERE 1=1";
        $params = [];
    
        if (isset($filters['is_approved'])) {
            $query .= " AND m.is_approved = :is_approved";
            $params[':is_approved'] = $filters['is_approved'];
        }
    
        if (!empty($filters['search'])) {
            $query .= " AND (m.first_name LIKE :search OR m.last_name LIKE :search OR m.email LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }
    
        if (!empty($filters['city'])) {
            $query .= " AND m.city = :city";
            $params[':city'] = $filters['city'];
        }
    
        if (!empty($filters['date_from'])) {
            $query .= " AND m.inscription_date >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
    
        if (!empty($filters['date_to'])) {
            $query .= " AND m.inscription_date <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
    
        if (!empty($filters['membership_type_id'])) {
            $query .= " AND m.membership_type_id = :membership_type_id";
            $params[':membership_type_id'] = $filters['membership_type_id'];
        }
    
        $query .= " ORDER BY m.inscription_date DESC";
    
        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
    
    public function approve_member($member_id) {
        $c = $this->connect();
        $query = "UPDATE members SET is_approved = true WHERE member_id = :member_id";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([':member_id' => $member_id]);
        $this->disconnect($c);
        return $result;
    }

    public function delete_member($member_id) {
        $c = $this->connect();
        $query = "DELETE FROM members WHERE member_id = :member_id";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([':member_id' => $member_id]);
        $this->disconnect($c);
        return $result;
    }

    public function get_all_member_ids() {
        $c = $this->connect();
        if (!$c) return false;

        try {
            $query = "SELECT member_id FROM members WHERE is_approved = 1";
            $stmt = $c->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $ex) {
            return false;
        } finally {
            $this->disconnect($c);
        }
    }
}

