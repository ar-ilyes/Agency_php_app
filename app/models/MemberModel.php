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
            $stmt = $c->prepare("INSERT INTO members (first_name, last_name, email, address, city, membership_type_id, user_id) 
                                VALUES (:first_name, :last_name, :email, :address, :city, :membership_type_id, :user_id)");
            $stmt->execute([
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':email' => $data['email'],
                ':address' => $data['address'],
                ':city' => $data['city'],
                ':membership_type_id' => $data['membership_type_id'],
                ':user_id' => $userId
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
    
}

