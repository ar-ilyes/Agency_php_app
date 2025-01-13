<?php
class EventModel {
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

    public function get_all_events() {
        $c = $this->connect();
        $query = "
            SELECT 
                e.*,
                COUNT(ev.id) as current_volunteers
            FROM EVENT e
            LEFT JOIN EVENT_VOLUNTEER ev ON e.id = ev.event_id AND ev.status = 'approved'
            WHERE e.date_end >= CURRENT_TIMESTAMP
            GROUP BY e.id
            ORDER BY e.date_start ASC
        ";
        $result = $c->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }

    public function register_volunteer($event_id, $member_id) {
        $c = $this->connect();
        
        // Check if already registered
        $check_query = "SELECT id FROM EVENT_VOLUNTEER WHERE event_id = ? AND member_id = ?";
        $check_stmt = $c->prepare($check_query);
        $check_stmt->execute([$event_id, $member_id]);
        
        if ($check_stmt->rowCount() > 0) {
            $this->disconnect($c);
            return ['success' => false, 'message' => 'Already registered for this event'];
        }
        
        // Check if event is full
        $capacity_query = "
            SELECT 
                e.max_volunteers,
                COUNT(ev.id) as current_volunteers
            FROM EVENT e
            LEFT JOIN EVENT_VOLUNTEER ev ON e.id = ev.event_id AND ev.status = 'approved'
            WHERE e.id = ?
            GROUP BY e.id
        ";
        $capacity_stmt = $c->prepare($capacity_query);
        $capacity_stmt->execute([$event_id]);
        $capacity_info = $capacity_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($capacity_info['current_volunteers'] >= $capacity_info['max_volunteers']) {
            $this->disconnect($c);
            return ['success' => false, 'message' => 'Event is full'];
        }
        
        // Register volunteer
        $query = "INSERT INTO EVENT_VOLUNTEER (event_id, member_id) VALUES (?, ?)";
        $stmt = $c->prepare($query);
        $result = $stmt->execute([$event_id, $member_id]);
        
        $this->disconnect($c);
        return [
            'success' => $result,
            'message' => $result ? 'Successfully registered' : 'Registration failed'
        ];
    }

    public function get_member_events($member_id) {
        $c = $this->connect();
        $query = "
            SELECT 
                e.*,
                ev.status as registration_status,
                ev.registration_date
            FROM EVENT e
            JOIN EVENT_VOLUNTEER ev ON e.id = ev.event_id
            WHERE ev.member_id = ?
            ORDER BY e.date_start ASC
        ";
        $stmt = $c->prepare($query);
        $stmt->execute([$member_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->disconnect($c);
        return $result;
    }
}
