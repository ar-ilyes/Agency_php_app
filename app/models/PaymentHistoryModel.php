<?php
class PaymentHistoryModel {
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

    public function get_all_payments($filters = []) {
        $c = $this->connect();
        $query = "SELECT ph.*, m.first_name, m.last_name, m.email 
                 FROM payment_history ph 
                 JOIN members m ON ph.member_id = m.member_id 
                 WHERE 1=1";
        $params = [];

        if (!empty($filters['payment_type'])) {
            $query .= " AND ph.payment_type = :payment_type";
            $params[':payment_type'] = $filters['payment_type'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (m.first_name LIKE :search OR m.last_name LIKE :search OR m.email LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND ph.payment_date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND ph.payment_date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $query .= " ORDER BY ph.payment_date DESC";

        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function get_total_payments() {
        $c = $this->connect();
        $query = "SELECT COUNT(*) FROM payment_history";
        $result = $c->query($query)->fetchColumn();
        $this->disconnect($c);
        return $result;
    }
    
    public function get_payments_by_type() {
        $c = $this->connect();
        $query = "SELECT payment_type, COUNT(*) as count 
                 FROM payment_history 
                 GROUP BY payment_type";
        $result = $c->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
        $this->disconnect($c);
        return $result;
    }
    
    public function get_monthly_payments() {
        $c = $this->connect();
        $query = "SELECT 
                    DATE_FORMAT(payment_date, '%Y-%m') as month,
                    COUNT(*) as count,
                    payment_type,
                    COUNT(*) as type_count
                  FROM payment_history 
                  GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), payment_type
                  ORDER BY month DESC, payment_type
                  LIMIT 12";
        $result = $c->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function get_member_payments($member_id) {
        $c = $this->connect();
        $query = "SELECT * FROM payment_history 
                 WHERE member_id = :member_id
                 ORDER BY payment_date DESC";
        
        $stmt = $c->prepare($query);
        $stmt->execute([':member_id' => $member_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
}
