<?php
class MembershipTypeModel {
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

    public function get_all(){
        $c=$this->connect();
        $q="SELECT * FROM membership_types";
        $r=$this->query($c, $q);
        //how to print the result
        $result = $r->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
        // return [
        //     1 => ['membership_type_id' => 1, 'name' => 'Basic'],
        //     2 => ['membership_type_id' => 2, 'name' => 'Premium'],
        //     3 => ['membership_type_id' => 3, 'name' => 'VIP'],
        // ];
    }
}
