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

    public function get_standard_discounts_of_partner($partner_id){
        $c = $this->connect();
        $q = "SELECT * FROM STANDARD_DISCOUNT WHERE partner_id = '{$partner_id}'";
        $result = $c->query($q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
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
            JOIN PARTNER p ON sd.id = p.id
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
        if (!empty($filters['partner_id'])) {
            error_log('partner_id');
            $query .= " AND p.id = ?";
            $params[] = $filters['partner_id'];
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

    public function get_special_offers_of_partner($partner_id){
        $c = $this->connect();
        $q = "SELECT * FROM SPECIAL_OFFER WHERE partner_id = '{$partner_id}'";
        $result = $c->query($q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
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
            JOIN PARTNER p ON so.id = p.id
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
        if(!empty($filters['partner_id'])) {
            $query .= " AND p.id = ?";
            $params[] = $filters['partner_id'];
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

    public function get_advantages_of_partner($partner_id){
        $c = $this->connect();
        $q = "SELECT * FROM ADVANTAGE WHERE partner_id = '{$partner_id}'";
        $result = $c->query($q)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
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
            JOIN PARTNER p ON a.id = p.id
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

        if (!empty($filters['partner_id'])) {
            $query .= " AND p.id = ?";
            $params[] = $filters['partner_id'];
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

    // Standard Discount CRUD
    public function create_standard_discount($data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            // Insert into STANDARD_DISCOUNT
            $query = "INSERT INTO STANDARD_DISCOUNT (partner_id, description, discount_value, discount_type) 
                     VALUES (:partner_id, :description, :discount_value, :discount_type)";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':partner_id' => $data['partner_id'],
                ':description' => $data['description'],
                ':discount_value' => $data['discount_value'],
                ':discount_type' => $data['discount_type']
            ]);
            
            $discount_id = $c->lastInsertId();
            
            // Insert eligibility for each membership type
            foreach ($data['membership_types'] as $type_id => $bonus_value) {
                $query = "INSERT INTO DISCOUNT_ELIGIBILITY (discount_id, membership_type_id, is_eligible, bonus_value)
                         VALUES (:discount_id, :type_id, 1, :bonus_value)";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':discount_id' => $discount_id,
                    ':type_id' => $type_id,
                    ':bonus_value' => $bonus_value
                ]);
            }
            
            $c->commit();
            return $discount_id;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function update_standard_discount($id, $data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            // Update STANDARD_DISCOUNT
            $query = "UPDATE STANDARD_DISCOUNT 
                     SET description = :description,
                         discount_value = :discount_value,
                         discount_type = :discount_type
                     WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':description' => $data['description'],
                ':discount_value' => $data['discount_value'],
                ':discount_type' => $data['discount_type']
            ]);
            
            // Update eligibilities
            foreach ($data['membership_types'] as $type_id => $bonus_value) {
                $query = "INSERT INTO DISCOUNT_ELIGIBILITY (discount_id, membership_type_id, is_eligible, bonus_value)
                         VALUES (:discount_id, :type_id, 1, :bonus_value)
                         ON DUPLICATE KEY UPDATE 
                         is_eligible = 1,
                         bonus_value = :bonus_value";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':discount_id' => $id,
                    ':type_id' => $type_id,
                    ':bonus_value' => $bonus_value
                ]);
            }
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function delete_standard_discount($id) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            // Delete eligibilities first
            $query = "DELETE FROM DISCOUNT_ELIGIBILITY WHERE discount_id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            // Delete discount
            $query = "DELETE FROM STANDARD_DISCOUNT WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    // Special Offer CRUD
    public function create_special_offer($data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "INSERT INTO SPECIAL_OFFER (partner_id, description, discount_value, start_date, end_date, offer_type) 
                     VALUES (:partner_id, :description, :discount_value, :start_date, :end_date, :offer_type)";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':partner_id' => $data['partner_id'],
                ':description' => $data['description'],
                ':discount_value' => $data['discount_value'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date'],
                ':offer_type' => $data['offer_type']
            ]);
            
            $offer_id = $c->lastInsertId();
            
            foreach ($data['membership_types'] as $type_id => $bonus_value) {
                $query = "INSERT INTO OFFER_ELIGIBILITY (offer_id, membership_type_id, is_eligible, bonus_value)
                         VALUES (:offer_id, :type_id, 1, :bonus_value)";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':offer_id' => $offer_id,
                    ':type_id' => $type_id,
                    ':bonus_value' => $bonus_value
                ]);
            }
            
            $c->commit();
            return $offer_id;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function update_special_offer($id, $data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "UPDATE SPECIAL_OFFER 
                     SET description = :description,
                         discount_value = :discount_value,
                         start_date = :start_date,
                         end_date = :end_date,
                         offer_type = :offer_type
                     WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':description' => $data['description'],
                ':discount_value' => $data['discount_value'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date'],
                ':offer_type' => $data['offer_type']
            ]);
            
            foreach ($data['membership_types'] as $type_id => $bonus_value) {
                $query = "INSERT INTO OFFER_ELIGIBILITY (offer_id, membership_type_id, is_eligible, bonus_value)
                         VALUES (:offer_id, :type_id, 1, :bonus_value)
                         ON DUPLICATE KEY UPDATE 
                         is_eligible = 1,
                         bonus_value = :bonus_value";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':offer_id' => $id,
                    ':type_id' => $type_id,
                    ':bonus_value' => $bonus_value
                ]);
            }
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function delete_special_offer($id) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "DELETE FROM OFFER_ELIGIBILITY WHERE offer_id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $query = "DELETE FROM SPECIAL_OFFER WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    // Advantage CRUD
    public function create_advantage($data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "INSERT INTO ADVANTAGE (partner_id, description, advantage_type) 
                     VALUES (:partner_id, :description, :advantage_type)";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':partner_id' => $data['partner_id'],
                ':description' => $data['description'],
                ':advantage_type' => $data['advantage_type']
            ]);
            
            $advantage_id = $c->lastInsertId();
            
            foreach ($data['membership_types'] as $type_id) {
                $query = "INSERT INTO ADVANTAGE_ELIGIBILITY (advantage_id, membership_type_id, is_eligible)
                         VALUES (:advantage_id, :type_id, 1)";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':advantage_id' => $advantage_id,
                    ':type_id' => $type_id
                ]);
            }
            
            $c->commit();
            return $advantage_id;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function update_advantage($id, $data) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "UPDATE ADVANTAGE 
                     SET description = :description,
                         advantage_type = :advantage_type
                     WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':description' => $data['description'],
                ':advantage_type' => $data['advantage_type']
            ]);
            
            // Delete existing eligibilities
            $query = "DELETE FROM ADVANTAGE_ELIGIBILITY WHERE advantage_id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            // Insert new eligibilities
            foreach ($data['membership_types'] as $type_id) {
                $query = "INSERT INTO ADVANTAGE_ELIGIBILITY (advantage_id, membership_type_id, is_eligible)
                         VALUES (:advantage_id, :type_id, 1)";
                $stmt = $c->prepare($query);
                $stmt->execute([
                    ':advantage_id' => $id,
                    ':type_id' => $type_id
                ]);
            }
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }

    public function delete_advantage($id) {
        $c = $this->connect();
        try {
            $c->beginTransaction();
            
            $query = "DELETE FROM ADVANTAGE_ELIGIBILITY WHERE advantage_id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $query = "DELETE FROM ADVANTAGE WHERE id = :id";
            $stmt = $c->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $c->commit();
            return true;
        } catch (Exception $e) {
            $c->rollBack();
            throw $e;
        } finally {
            $this->disconnect($c);
        }
    }
}
