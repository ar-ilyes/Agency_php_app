<?php
class DonationModel {
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

    public function create_donation($member_id, $amount, $payment_receipt) {
        $c = $this->connect();
        $query = "INSERT INTO DONATION (member_id, amount, payment_receipt) VALUES (?, ?, ?)";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([$member_id, $amount, $payment_receipt]);
        $id = $c->lastInsertId();
        $this->disconnect($c);
        return $result ? $id : false;
    }
}
