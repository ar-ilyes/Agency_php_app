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
        $q = "SELECT id, name, city, category FROM PARTNER";
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
}
