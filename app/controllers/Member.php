<?php 
class Member
{
    use Controller;

    private $memberModel;

    public function __construct() {
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];
        
        // Get member data
        $memberData = $this->memberModel->get_member_by_id($member_id);
        
        // Get membership type
        $membershipType = $this->memberModel->get_membership_type($memberData['membership_type_id']);
        
        // Create view instance and pass data
        $view = new MemberProfileView();
        $view->setData([
            'member' => $memberData,
            'membershipType' => $membershipType
        ]);
        
        // Call the view's index method
        $view->index();
    }

    // Method that view can call for dynamic content
    public function getMemberData($member_id) {
        return $this->memberModel->get_member_by_id($member_id);
    }

    public function getMembershipType($membership_type_id) {
        return $this->memberModel->get_membership_type($membership_type_id);
    }
}
