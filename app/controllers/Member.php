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
		$favorites = $this->memberModel->get_member_favorites($member_id);
		error_log('Favorites: ' . json_encode($favorites));

		// Parse the URL
		$url = $_GET['url'] ?? '';
		$urlParts = explode('/', trim($url, '/'));
	
		// Check if the URL matches the pattern /member/remove-favorite/{id}
		if (count($urlParts) === 3 && $urlParts[0] === 'member' && $urlParts[1] === 'remove-favorite') {
			$favoriteId = $urlParts[2];
			$this->remove_favorite($favoriteId);
			return;
		}
        
        // Create view instance and pass data
        $view = new MemberProfileView();
        $view->setData([
            'member' => $memberData,
            'membershipType' => $membershipType,
			'favorites' => $favorites
        ]);
        
        // Call the view's index method
        $view->index();
    }

	public function remove_favorite($partner_id) {
		$user = $_SESSION['user'];
		$member_id = $user['entity_id'];
        $this->memberModel->remove_favorite($member_id, $partner_id);
        header('Location: /member');
    }

    // Method that view can call for dynamic content
    public function getMemberData($member_id) {
        return $this->memberModel->get_member_by_id($member_id);
    }

    public function getMembershipType($membership_type_id) {
        return $this->memberModel->get_membership_type($membership_type_id);
    }
}
