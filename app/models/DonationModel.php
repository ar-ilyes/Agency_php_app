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
        $query = "INSERT INTO DONATION (member_id, amount, payment_receipt, is_validated) VALUES (?, ?, ?, 0)";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([$member_id, $amount, $payment_receipt]);
        $id = $c->lastInsertId();
        $this->disconnect($c);
        return $result ? $id : false;
    }
    public function get_all_donations($filters = []) {
        $c = $this->connect();
        $query = "SELECT d.*, m.first_name, m.last_name, m.email 
                 FROM DONATION d 
                 JOIN members m ON d.member_id = m.member_id 
                 WHERE 1=1";
        $params = [];

        if (isset($filters['is_validated'])) {
            $query .= " AND d.is_validated = :is_validated";
            $params[':is_validated'] = $filters['is_validated'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (m.first_name LIKE :search OR m.last_name LIKE :search OR m.email LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }

        if (!empty($filters['min_amount'])) {
            $query .= " AND d.amount >= :min_amount";
            $params[':min_amount'] = $filters['min_amount'];
        }

        if (!empty($filters['max_amount'])) {
            $query .= " AND d.amount <= :max_amount";
            $params[':max_amount'] = $filters['max_amount'];
        }

        $query .= " ORDER BY d.donation_date DESC";

        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function validate_donation($donation_id) {
        $c = $this->connect();
        $query = "UPDATE DONATION SET is_validated = 1 WHERE id = :donation_id";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([':donation_id' => $donation_id]);
        $this->disconnect($c);
        return $result;
    }

    public function get_donation_by_id($donation_id) {
        $c = $this->connect();
        $query = "SELECT d.*, m.first_name, m.last_name, m.email 
                 FROM DONATION d 
                 JOIN members m ON d.member_id = m.member_id 
                 WHERE d.id = :donation_id";
        $stmt = $c->prepare($query);
        $stmt->execute([':donation_id' => $donation_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
}
