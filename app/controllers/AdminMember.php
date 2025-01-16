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
        if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
            header('Location: /auth');
            return;
        }
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));

        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (count($urlParts) === 2 && $urlParts[0] === 'adminMember' && $urlParts[1] === 'approve') {
                $this->approve();
                return;
            }
            if (count($urlParts) === 2 && $urlParts[0] === 'adminMember' && $urlParts[1] === 'delete') {
                $this->delete();
                return;
            }
        }

        $filters = [
            'is_approved' => isset($_GET['is_approved']) ? filter_var($_GET['is_approved'], FILTER_VALIDATE_BOOLEAN) : null,
            'search' => $_GET['search'] ?? null,
            'city' => $_GET['city'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'membership_type_id' => $_GET['membership_type_id'] ?? null
        ];


        $members = $this->memberModel->get_filtered_members($filters);
        
        $membership_types = $this->membershipTypeModel->get_all();

        $cities = array_unique(array_column($members, 'city'));

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

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /adminMember');
            return;
        }

        $member_id = $_POST['member_id'];
        $success = $this->memberModel->delete_member($member_id);

        if ($success) {
            header('Location: /adminMember?success=deleted');
        } else {
            header('Location: /adminMember?error=deletion_failed');
        }
    }
}
