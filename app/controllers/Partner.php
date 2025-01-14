<?php
class Partner {
    use Controller;
    private $partnerModel;
    private $memberModel;

    public function __construct() {
        $this->partnerModel = new PartnerModel();
        $this->memberModel = new MemberModel();
    }

    public function index() {
        $user = $_SESSION['user'];
        $partner_id = $user['entity_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['verify_member'])) {
                $this->verify_member();
                return;
            } else {
                $this->update();
                return;
            }
        }
        
        // Get partner data
        $partnerData = $this->partnerModel->get_partner_by_id($partner_id);

        $verifiedMember = $_SESSION['verified_member'] ?? null;
        if($verifiedMember) {
            unset($_SESSION['verified_member']); // Clear after use
        }
        
        // Create view instance and pass data
        $view = new PartnerProfileView();
        $view->setData([
            'partner' => $partnerData,
            'verifiedMember' => $verifiedMember
        ]);
        
        // Call the view's index method
        $view->index();
    }

    private function verify_member() {
        $member_id = $_POST['member_id'] ?? null;
        
        if($member_id) {
            $memberData = $this->memberModel->get_member_by_id($member_id);
            if($memberData) {
                // Get membership type
                $membershipType = $this->memberModel->get_membership_type($memberData['membership_type_id']);
                $memberData['membership_type'] = $membershipType;
                
                $_SESSION['verified_member'] = $memberData;
                header('Location: /partner?verified=1');
                return;
            }
        }
        
        header('Location: /partner?error=member_not_found');
    }

    // Method that view can call for dynamic content
    public function getPartnerData($partner_id) {
        return $this->partnerModel->get_partner_by_id($partner_id);
    }

    public function update() {
        $user = $_SESSION['user'];
        $partner_id = $user['entity_id'];
        
        // Handle file upload for logo
        $logo = $_FILES['logo'] ?? null;
        $logo_path = null;
        
        if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/logos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $logo_path = $upload_dir . uniqid() . '_' . basename($logo['name']);
            move_uploaded_file($logo['tmp_name'], $logo_path);
        }
        
        $data = [
            'name' => $_POST['name'],
            'city' => $_POST['city'],
            'category' => $_POST['category'],
            'logo' => $logo_path ?? $_POST['current_logo']
        ];
        
        $success = $this->partnerModel->update_partner($partner_id, $data);
        
        if ($success) {
            header('Location: /partner?success=1');
        } else {
            header('Location: /partner?error=1');
        }
    }
}
