<?php 
class PartnerProfileView extends BaseView {
    protected $data = [];
    protected $controller;
    private $card;

    public function __construct() {
        $user = $_SESSION['user'];
        $partner_id = $user['entity_id'];
        $this->card = "/cards/partner_card_$partner_id.png";
    }

    public function index() {
        $this->renderHead();
        $this->render_profile_header();
        $this->render_verify_member_section();
    }

    private function render_profile_header() {
        $partner = $this->data['partner'];
        $showEditForm = isset($_GET['edit']);
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-wrap md:flex-nowrap gap-8">
                <!-- Partner Info Section -->
                <div class="w-full md:w-2/3">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Partner Information</h2>
                        <?php if (!$showEditForm): ?>
                            <a href="/partner?edit=1" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                Edit Profile
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($showEditForm): ?>
                        <form action="/partner" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="current_logo" value="<?= htmlspecialchars($partner['logo'] ?? '') ?>">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-gray-600">Name</label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($partner['name']) ?>"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-gray-600">City</label>
                                    <input type="text" name="city" value="<?= htmlspecialchars($partner['city']) ?>"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-gray-600">Category</label>
                                    <input type="text" name="category" value="<?= htmlspecialchars($partner['category']) ?>"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-gray-600">Logo</label>
                                    <input type="file" name="logo" accept="image/*"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end space-x-4">
                                <a href="/partner" class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <p class="text-gray-600">Partner ID</p>
                                <p class="font-medium">PTN<?= htmlspecialchars($partner['id']) ?></p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">Name</p>
                                <p class="font-medium"><?= htmlspecialchars($partner['name']) ?></p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">City</p>
                                <p class="font-medium"><?= htmlspecialchars($partner['city']) ?></p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">Category</p>
                                <p class="font-medium"><?= htmlspecialchars($partner['category']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Partner Card Section -->
                <div class="w-full md:w-1/3">
                    <div class="bg-gray-100 rounded-lg p-4 aspect-[3/2] relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 p-6 text-white">
                            <div class="flex items-center space-x-4 mb-4">
                                <img src="<?= htmlspecialchars($partner['logo'] ?? '/images/default-logo.png') ?>" 
                                     alt="Partner Logo" class="w-16 h-16 rounded-full bg-white p-2">
                                <div>
                                    <h3 class="text-xl font-bold"><?= htmlspecialchars($partner['name']) ?></h3>
                                    <p class="text-sm opacity-90"><?= htmlspecialchars($partner['category']) ?></p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm">ID: PTN<?= htmlspecialchars($partner['id']) ?></p>
                                <p class="text-sm"><?= htmlspecialchars($partner['city']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_verify_member_section() {
        $verifiedMember = $this->data['verifiedMember'];
        $error = isset($_GET['error']) && $_GET['error'] === 'member_not_found';
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Verify Member Information</h2>
                
                <!-- Verification Form -->
                <form action="/partner" method="POST" class="mb-6">
                    <input type="hidden" name="verify_member" value="1">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Member ID
                            </label>
                            <input type="text" id="member_id" name="member_id" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                   placeholder="Enter member ID">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                Verify
                            </button>
                        </div>
                    </div>
                </form>

                <?php if($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Member not found. Please check the ID and try again.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($verifiedMember): ?>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-green-800">Verified Member Information</h3>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                Active Member
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Member ID</p>
                                <p class="font-medium">MEM<?= htmlspecialchars($verifiedMember['member_id']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">
                                    <?= htmlspecialchars($verifiedMember['first_name'] . ' ' . $verifiedMember['last_name']) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium"><?= htmlspecialchars($verifiedMember['email']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">City</p>
                                <p class="font-medium"><?= htmlspecialchars($verifiedMember['city']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Membership Type</p>
                                <p class="font-medium">
                                    <?= htmlspecialchars($verifiedMember['membership_type']['name'] ?? 'Standard') ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="font-medium text-green-600">Valid</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }


    public function setData($data) {
        $this->data = $data;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }
}
