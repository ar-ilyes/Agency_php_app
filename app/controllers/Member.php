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
        
        $memberData = $this->memberModel->get_member_by_id($member_id);
        $membershipType = $this->memberModel->get_membership_type($memberData['membership_type_id']);
		$favorites = $this->memberModel->get_member_favorites($member_id);
		error_log('Favorites: ' . json_encode($favorites));// bach ntesti

		$url = $_GET['url'] ?? '';
		$urlParts = explode('/', trim($url, '/'));

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (count($urlParts) === 2 && $urlParts[1] === 'upgrade') {
				echo 'upgrade';
				$this->upgrade();
				return;
			}else{
			$this->update();
			return;
			}
		}
	
		if (count($urlParts) === 3 && $urlParts[0] === 'member' && $urlParts[1] === 'remove-favorite') {
			$favoriteId = $urlParts[2];
			$this->remove_favorite($favoriteId);
			return;
		}
		
        
		$donationModel = new DonationModel();
		$eventModel = new EventModel();
		$aidRequestModel = new AidRequestModel();
		
		$history = [
			'donations' => $donationModel->get_member_donations($member_id),
			'events' => $eventModel->get_member_events($member_id),
			'aid_requests' => $aidRequestModel->get_member_requests($member_id)
		];
	
        $view = new MemberProfileView();
        $view->setData([
            'member' => $memberData,
            'membershipType' => $membershipType,
			'favorites' => $favorites,
			'history' => $history
        ]);
        $view->index();
    }

	public function remove_favorite($partner_id) {
		$user = $_SESSION['user'];
		$member_id = $user['entity_id'];
        $this->memberModel->remove_favorite($member_id, $partner_id);
        header('Location: /member');
    }

    public function getMemberData($member_id) {
        return $this->memberModel->get_member_by_id($member_id);
    }

    public function getMembershipType($membership_type_id) {
        return $this->memberModel->get_membership_type($membership_type_id);
    }

	public function update() {
		$user = $_SESSION['user'];
		$member_id = $user['entity_id'];
		
		$photo = $_FILES['photo'] ?? null;
		$photo_path = null;
		
		if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
			$upload_dir = 'uploads/photos/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			
			$photo_path = $upload_dir . uniqid() . '_' . basename($photo['name']);
			move_uploaded_file($photo['tmp_name'], $photo_path);
		}
		
		$data = [
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'email' => $_POST['email'],
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'photo' => $photo_path ?? $_POST['current_photo']
		];
		
		$success = $this->memberModel->update_member($member_id, $data);
		
		if ($success) {
			header('Location: /member?success=1');
		} else {
			header('Location: /member?error=1');
		}
	}

	public function upgrade() {
		if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'member') {
            header('Location: /auth');
            return;
        }
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: /member');
			return;
		}
		
		$user = $_SESSION['user'];
		$member_id = $user['entity_id'];
		
		$receipt = $_FILES['payment_receipt'] ?? null;
		$receipt_path = null;
		if ($receipt && $receipt['error'] === UPLOAD_ERR_OK) {
			$upload_dir = 'uploads/receipts/';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$receipt_path = $upload_dir . uniqid() . '_' . basename($receipt['name']);
			move_uploaded_file($receipt['tmp_name'], $receipt_path);
		}
		
		$data = [
			'membership_type_id' => $_POST['membership_type_id'],
			'payment_receipt' => $receipt_path
		];
		
		$success = $this->memberModel->upgrade_membership($member_id, $data);
		if ($success) {
			header('Location: /member?success=upgraded');
		} else {
			header('Location: /member?error=upgrade_failed');
		}
	}

	
}
