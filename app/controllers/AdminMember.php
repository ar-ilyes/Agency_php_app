<?php
class AdminMember {
    use Controller;
    private $memberModel;
    private $membershipTypeModel;

    public function __construct() {
        $this->memberModel = new MemberModel();
        $this->membershipTypeModel = new MembershipTypeModel();
    }

    public function index() {
        // Parse the URL
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));

        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminMember' && $urlParts[1] === 'approve') {
                $this->approve();
                return;
            }
        }

        // Get filters from GET parameters
        $filters = [
            'is_approved' => isset($_GET['is_approved']) ? filter_var($_GET['is_approved'], FILTER_VALIDATE_BOOLEAN) : null,
            'search' => $_GET['search'] ?? null,
            'city' => $_GET['city'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'membership_type_id' => $_GET['membership_type_id'] ?? null
        ];

        // Get members with filters
        $members = $this->memberModel->get_filtered_members($filters);
        
        // Get membership types for filter
        $membership_types = $this->membershipTypeModel->get_all();

        // Get unique cities for filter
        $cities = array_unique(array_column($members, 'city'));

        // Create view instance and pass data
        $view = new AdminMemberView();
        $view->setData([
            'members' => $members,
            'membership_types' => $membership_types,
            'cities' => $cities,
            'filters' => $filters
        ]);
        $view->setController($this);
        $view->index();
    }

    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminMember');
            return;
        }

        $member_id = $_POST['member_id'];
        $success = $this->memberModel->approve_member($member_id);

        if ($success) {
            header('Location: /adminMember?success=approved');
        } else {
            header('Location: /adminMember?error=approve_failed');
        }
    }
}
