<?php
class UserModel {
    private $dbname = "association_db";
    private $host = "127.0.0.1";
    private $port = "3306";
    private $user = "root";
    private $password = "";

    private function connect() {
        $dsn = "mysql:dbname={$this->dbname};host={$this->host};port={$this->port}";
        try {
            $c = new PDO($dsn, $this->user, $this->password);
            $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $c;
        } catch (PDOException $ex) {
            printf("Database connection error: %s", $ex->getMessage());
            exit();
        }
    }

    private function disconnect(&$c) {
        $c = null;
    }

    public function verify_user($email) {
        $c = $this->connect();
        $stmt = $c->prepare("
            SELECT u.*, 
                   CASE 
                       WHEN m.user_id IS NOT NULL THEN m.member_id 
                       WHEN p.user_id IS NOT NULL THEN p.id 
                   END as entity_id 
            FROM users u 
            LEFT JOIN members m ON u.id = m.user_id 
            LEFT JOIN PARTNER p ON u.id = p.user_id 
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log(json_encode($result));
        $this->disconnect($c);
        return $result;
    }
}
