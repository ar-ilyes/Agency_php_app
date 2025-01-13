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

    public function get_standard_discounts($membership_type_id, $filters = [], $sort = null) {
        $c = $this->connect();
        $query = "
            SELECT 
                sd.*, 
                p.name as partner_name,
                p.category as partner_category,
                p.city as partner_city,
                de.bonus_value
            FROM STANDARD_DISCOUNT sd
            JOIN PARTNER p ON sd.partner_id = p.id
            JOIN DISCOUNT_ELIGIBILITY de ON sd.id = de.discount_id
            WHERE de.membership_type_id = ? AND de.is_eligible = 1
        ";
        
        $params = [$membership_type_id];
        
        // Add filters
        if (!empty($filters['category'])) {
            $query .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['city'])) {
            $query .= " AND p.city = ?";
            $params[] = $filters['city'];
        }
        
        // Add sorting
        if ($sort) {
            $query .= " ORDER BY " . $this->get_sort_clause($sort);
        }
        
        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    public function get_special_offers($membership_type_id, $filters = [], $sort = null) {
        $c = $this->connect();
        $query = "
            SELECT 
                so.*, 
                p.name as partner_name,
                p.category as partner_category,
                p.city as partner_city,
                oe.bonus_value
            FROM SPECIAL_OFFER so
            JOIN PARTNER p ON so.partner_id = p.id
            JOIN OFFER_ELIGIBILITY oe ON so.id = oe.offer_id
            WHERE oe.membership_type_id = ? 
            AND oe.is_eligible = 1
            AND so.end_date >= CURRENT_DATE()
        ";
        
        $params = [$membership_type_id];
        
        if (!empty($filters['category'])) {
            $query .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['city'])) {
            $query .= " AND p.city = ?";
            $params[] = $filters['city'];
        }
        
        if ($sort) {
            $query .= " ORDER BY " . $this->get_sort_clause($sort);
        }
        
        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    public function get_advantages($membership_type_id, $filters = [], $sort = null) {
        $c = $this->connect();
        $query = "
            SELECT 
                a.*, 
                p.name as partner_name,
                p.category as partner_category,
                p.city as partner_city
            FROM ADVANTAGE a
            JOIN PARTNER p ON a.partner_id = p.id
            JOIN ADVANTAGE_ELIGIBILITY ae ON a.id = ae.advantage_id
            WHERE ae.membership_type_id = ? AND ae.is_eligible = 1
        ";
        
        $params = [$membership_type_id];
        
        if (!empty($filters['category'])) {
            $query .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['city'])) {
            $query .= " AND p.city = ?";
            $params[] = $filters['city'];
        }
        
        if ($sort) {
            $query .= " ORDER BY " . $this->get_sort_clause($sort);
        }
        
        $stmt = $c->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $results;
    }

    private function get_sort_clause($sort) {
        $valid_sorts = [
            'partner_name_asc' => 'p.name ASC',
            'partner_name_desc' => 'p.name DESC',
            'discount_value_asc' => 'discount_value ASC',
            'discount_value_desc' => 'discount_value DESC',
            'category_asc' => 'p.category ASC',
            'category_desc' => 'p.category DESC'
        ];
        
        return $valid_sorts[$sort] ?? 'p.name ASC';
    }

    public function get_categories() {
        $c = $this->connect();
        $query = "SELECT DISTINCT category FROM PARTNER ORDER BY category";
        $stmt = $c->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $this->disconnect($c);
        return $results;
    }

    public function get_cities() {
        $c = $this->connect();
        $query = "SELECT DISTINCT city FROM PARTNER ORDER BY city";
        $stmt = $c->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $this->disconnect($c);
        return $results;
    }
}
