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
                    <div class="mt-8 space-y-6">
                <!-- Standard Discounts -->
                <?php if(!empty($verifiedMember['benefits']['standard_discounts'])): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <span class="text-blue-600 mr-2">
                                <svg class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                </svg>
                            </span>
                            Eligible Standard Discounts
                        </h3>
                        <div class="space-y-4">
                            <?php foreach($verifiedMember['benefits']['standard_discounts'] as $discount): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium"><?= htmlspecialchars($discount['description']) ?></h4>
                                        <p class="text-sm text-gray-600">
                                            Type: <?= htmlspecialchars($discount['discount_type']) ?>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-green-600">
                                            <?= htmlspecialchars($discount['discount_value']) ?>%
                                        </span>
                                        <?php if($discount['bonus_value']): ?>
                                            <p class="text-sm text-blue-600">
                                                +<?= htmlspecialchars($discount['bonus_value']) ?>% bonus
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Special Offers -->
                <?php if(!empty($verifiedMember['benefits']['special_offers'])): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <span class="text-purple-600 mr-2">
                                <svg class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 2a1 1 0 011-1h8a1 1 0 011 1v2h3a1 1 0 011 1v12a1 1 0 01-1 1H2a1 1 0 01-1-1V5a1 1 0 011-1h3V2zm1 3H3v11h14V5h-3v1a1 1 0 01-1 1H7a1 1 0 01-1-1V5zm2-2v2h4V3H8z"/>
                                </svg>
                            </span>
                            Special Offers Available
                        </h3>
                        <div class="space-y-4">
                            <?php foreach($verifiedMember['benefits']['special_offers'] as $offer): ?>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-medium"><?= htmlspecialchars($offer['description']) ?></h4>
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                            <?= htmlspecialchars($offer['offer_type']) ?>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <p class="text-gray-600">
                                            Valid until: <?= date('M d, Y', strtotime($offer['end_date'])) ?>
                                        </p>
                                        <span class="font-bold text-green-600">
                                            Save <?= htmlspecialchars($offer['discount_value']) ?>%
                                            <?php if($offer['bonus_value']): ?>
                                                + <?= htmlspecialchars($offer['bonus_value']) ?>% bonus
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Advantages -->
                <?php if(!empty($verifiedMember['benefits']['advantages'])): ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <span class="text-yellow-600 mr-2">
                                <svg class="inline-block w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </span>
                            Member Advantages
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach($verifiedMember['benefits']['advantages'] as $advantage): ?>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <h4 class="font-medium mb-2"><?= htmlspecialchars($advantage['description']) ?></h4>
                                    <p class="text-sm text-gray-600">
                                        Type: <?= htmlspecialchars($advantage['advantage_type']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(empty($verifiedMember['benefits']['standard_discounts']) && 
                        empty($verifiedMember['benefits']['special_offers']) && 
                        empty($verifiedMember['benefits']['advantages'])): ?>
                    <div class="text-center py-8 text-gray-500">
                        No benefits available for this membership type.
                    </div>
                <?php endif; ?>
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
