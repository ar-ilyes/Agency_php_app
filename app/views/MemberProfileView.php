<?php 
class MemberProfileView extends BaseView
{
    public function index()
    {
        $this->renderHead();
        // $this->render_top_navbar();
        if (true) {
            $this->render_expired_warning();
        }
        $this->render_profile_header();
        $this->render_favorites_section();
        $this->render_history_section();
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
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-wrap md:flex-nowrap gap-8">
                <!-- Member Info Section -->
                <div class="w-full md:w-2/3">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Member Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <p class="text-gray-600">Member ID</p>
                            <p class="font-medium">MEM123456</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Name</p>
                            <p class="font-medium">John Doe</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Date of Birth</p>
                            <p class="font-medium">01/15/1990</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Inscription Date</p>
                            <p class="font-medium">03/20/2024</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Phone Number</p>
                            <p class="font-medium">+213 555 123 456</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-gray-600">Membership Type</p>
                            <p class="font-medium">Premium</p>
                        </div>
                    </div>
                </div>
                
                <!-- Member Card Section -->
                <div class="w-full md:w-1/3">
                    <div class="bg-gray-100 rounded-lg p-4 aspect-[3/2] relative overflow-hidden">
                        <img src="/cards/card_17.png" alt="Member Photo" class="w-full h-full object-cover rounded">
                        <div class="absolute bottom-4 left-4 bg-white px-3 py-1 rounded-full text-sm">
                            Valid until: 03/20/2025
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_favorites_section()
    {
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Favorite Partners</h2>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Add Favorite Partner
                    </button>
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
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">Partner <?= $i ?></td>
                                <td class="px-6 py-4">City <?= $i ?></td>
                                <td class="px-6 py-4">
                                    <button class="text-red-600 hover:text-red-800">Remove</button>
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center gap-2 mt-4">
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Previous</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">1</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Next</button>
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
                
                <!-- History Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $types = ['Purchase', 'Discount Used', 'Account Update', 'Partner Added', 'Membership Renewal'];
                            for($i = 1; $i <= 5; $i++):
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= $types[array_rand($types)] ?></td>
                                <td class="px-6 py-4">Activity <?= $i ?></td>
                                <td class="px-6 py-4"><?= date('Y-m-d', strtotime("-$i day")) ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center gap-2 mt-4">
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Previous</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">1</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">3</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>
    <?php
    }
}