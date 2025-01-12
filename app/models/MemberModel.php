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

    public function insert_member($data){
        $c=$this->connect();
        $q="INSERT INTO members (first_name, last_name, email, address, city, membership_type_id) VALUES ('{$data['first_name']}', '{$data['last_name']}', '{$data['email']}', '{$data['address']}', '{$data['city']}', '{$data['membership_type_id']}')";
        $this->query($c, $q);
        $id=$c->lastInsertId();
        $this->disconnect($c);
        return $id;
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

    public function insert_member($data){
        $c=$this->connect();
        $q="INSERT INTO members (first_name, last_name, email, address, city, membership_type_id) VALUES ('{$data['first_name']}', '{$data['last_name']}', '{$data['email']}', '{$data['address']}', '{$data['city']}', '{$data['membership_type_id']}')";
        $this->query($c, $q);
        $id=$c->lastInsertId();
        $this->disconnect($c);
        return $id;
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
    
}


}

