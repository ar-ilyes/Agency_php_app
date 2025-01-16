<?php 
class MemberProfileView extends BaseView
{
    protected $data = [];
    protected $controller;
    private $card;
    public function __construct()
    {
        $user = $_SESSION['user'];
        $member_id = $user['entity_id'];
        $this->card = "/cards/card_$member_id.png";
    }
    public function index()
    {
        $this->renderHead();
        // $this->render_top_navbar();
        if ($this->isMembershipExpired()) {
            $this->render_expired_warning();
        }
        $this->render_profile_header();
        $this->render_favorites_section();
        $this->render_history_section();
    }
    private function isMembershipExpired() {
        // Implement your expiration logic here
        return false;
    }

    private function render_expired_warning()
    {
    ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 sticky top-0 z-50">
            <div class="flex items-center justify-between container mx-auto">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm0-9a1 1 0 011 1v3a1 1 0 11-2 0V8a1 1 0 011-1zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Your membership has expired! Please renew to continue enjoying member benefits.
                        </p>
                    </div>
                </div>
                <div>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        Renew Now
                    </button>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_profile_header()
{
    $member = $this->data['member'];
    $membershipType = $this->data['membershipType'];
    $showEditForm = isset($_GET['edit']);
?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 flex flex-wrap md:flex-nowrap gap-8">
            <!-- Member Info Section -->
            <div class="w-full md:w-2/3">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Member Information</h2>
                    <?php if (!$showEditForm): ?>
                        <a href="/member?edit=1" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            Edit Profile
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($showEditForm): ?>
                    <form action="/member" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="current_photo" value="<?= htmlspecialchars($member['photo']) ?>">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-gray-600">First Name</label>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($member['first_name']) ?>"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div class="space-y-2">
                                <label class="text-gray-600">Last Name</label>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($member['last_name']) ?>"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div class="space-y-2">
                                <label class="text-gray-600">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div class="space-y-2">
                                <label class="text-gray-600">City</label>
                                <input type="text" name="city" value="<?= htmlspecialchars($member['city']) ?>"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label class="text-gray-600">Address</label>
                                <input type="text" name="address" value="<?= htmlspecialchars($member['address']) ?>"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label class="text-gray-600">Photo</label>
                                <input type="file" name="photo" accept="image/*"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="/member" class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Save Changes
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <p class="text-gray-600">Member ID</p>
                            <p class="font-medium">MEM<?= htmlspecialchars($member['member_id']) ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Name</p>
                            <p class="font-medium"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Email</p>
                            <p class="font-medium"><?= htmlspecialchars($member['email']) ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">City</p>
                            <p class="font-medium"><?= htmlspecialchars($member['city']) ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Address</p>
                            <p class="font-medium"><?= htmlspecialchars($member['address']) ?></p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Membership Type</p>
                            <p class="font-medium"><?= htmlspecialchars($membershipType['name'] ?? 'Standard') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Member Card Section -->
            <div class="w-full md:w-1/3">
                <div class="bg-gray-100 rounded-lg p-4 aspect-[3/2] relative overflow-hidden">
                    <img src="<?= htmlspecialchars($this->card ?? '/cards/default_card.png') ?>" 
                         alt="Member Photo" class="w-full h-full object-cover rounded">
                    <div class="absolute bottom-4 left-4 bg-white px-3 py-1 rounded-full text-sm">
                        Valid until: 03/20/2025
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}


    private function render_favorites_section() {
        $favorites = $this->data['favorites'];
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Favorite Partners</h2>
                    <a href="/partnerSelect" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Add Favorite Partner
                    </a>
                </div>
                
                <!-- Favorites Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Partner Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">City</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($favorites as $favorite): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= htmlspecialchars($favorite['name']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($favorite['city']) ?></td>
                                <td class="px-6 py-4">
                                    <a href="/member/remove-favorite/<?= $favorite['id'] ?>" 
                                       class="text-red-600 hover:text-red-800">Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_history_section()
    {
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Activity History</h2>
                
                <!-- Tabs -->
                <div class="mb-6 border-b">
                    <div class="flex space-x-8">
                        <button onclick="showTab('donations')" class="px-4 py-2 border-b-2 border-blue-600 font-medium">
                            Donations
                        </button>
                        <button onclick="showTab('events')" class="px-4 py-2 border-b-2 border-transparent hover:border-gray-300">
                            Volunteering
                        </button>
                        <button onclick="showTab('aid')" class="px-4 py-2 border-b-2 border-transparent hover:border-gray-300">
                            Aid Requests
                        </button>
                    </div>
                </div>
                
                <!-- Donations Tab -->
                <div id="donations-tab">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($this->data['history']['donations'] as $donation): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">$<?= number_format($donation['amount'], 2) ?></td>
                                <td class="px-6 py-4"><?= date('Y-m-d', strtotime($donation['donation_date'])) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-sm rounded <?= $donation['is_validated'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= $donation['is_validated'] ? 'Validated' : 'Pending' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Events Tab -->
                <div id="events-tab" class="hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Event</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($this->data['history']['events'] as $event): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= htmlspecialchars($event['title']) ?></td>
                                <td class="px-6 py-4"><?= date('Y-m-d', strtotime($event['date_start'])) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-sm rounded <?= $event['registration_status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= ucfirst($event['registration_status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Aid Requests Tab -->
                <div id="aid-tab" class="hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($this->data['history']['aid_requests'] as $request): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= htmlspecialchars($request['aid_type']) ?></td>
                                <td class="px-6 py-4"><?= date('Y-m-d', strtotime($request['created_at'])) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded">
                                        Submitted
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script>
        function showTab(tabName) {
            // Hide all tabs
            document.getElementById('donations-tab').classList.add('hidden');
            document.getElementById('events-tab').classList.add('hidden');
            document.getElementById('aid-tab').classList.add('hidden');
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Update tab buttons
            const buttons = document.querySelectorAll('.border-b button');
            buttons.forEach(button => {
                button.classList.remove('border-blue-600');
                button.classList.add('border-transparent');
            });
            
            event.target.classList.remove('border-transparent');
            event.target.classList.add('border-blue-600');
        }
        </script>
    <?php
    }


    

    public function setData($data) {
        $this->data = $data;
    }

    public function setController($controller) {
        $this->controller = $controller;
    }
}