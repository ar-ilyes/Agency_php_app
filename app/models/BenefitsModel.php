<?php
class BenefitsModel {
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

    public function get_standard_discounts($membership_type_id) {
        $c = $this->connect();
        $query = "
            SELECT 
                sd.*, 
                p.name as partner_name,
                p.category as partner_category,
                de.bonus_value
            FROM STANDARD_DISCOUNT sd
            JOIN PARTNER p ON sd.partner_id = p.id
            JOIN DISCOUNT_ELIGIBILITY de ON sd.id = de.discount_id
            WHERE de.membership_type_id = ? AND de.is_eligible = 1
        ";
        $stmt = $c->prepare($query);
        $stmt->execute([$membership_type_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    public function get_special_offers($membership_type_id) {
        $c = $this->connect();
        $query = "
            SELECT 
                so.*, 
                p.name as partner_name,
                p.category as partner_category,
                oe.bonus_value
            FROM SPECIAL_OFFER so
            JOIN PARTNER p ON so.partner_id = p.id
            JOIN OFFER_ELIGIBILITY oe ON so.id = oe.offer_id
            WHERE oe.membership_type_id = ? 
            AND oe.is_eligible = 1
            AND so.end_date >= CURRENT_DATE()
        ";
        $stmt = $c->prepare($query);
        $stmt->execute([$membership_type_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    public function get_advantages($membership_type_id) {
        $c = $this->connect();
        $query = "
            SELECT 
                a.*, 
                p.name as partner_name,
                p.category as partner_category
            FROM ADVANTAGE a
            JOIN PARTNER p ON a.partner_id = p.id
            JOIN ADVANTAGE_ELIGIBILITY ae ON a.id = ae.advantage_id
            WHERE ae.membership_type_id = ? AND ae.is_eligible = 1
        ";
        $stmt = $c->prepare($query);
        $stmt->execute([$membership_type_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    public function get_membership_type($membership_type_id) {
        $c = $this->connect();
        $query = "SELECT * FROM membership_types WHERE membership_type_id = ?";
        $stmt = $c->prepare($query);
        $stmt->execute([$membership_type_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
}
