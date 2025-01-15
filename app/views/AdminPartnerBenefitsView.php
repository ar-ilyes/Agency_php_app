<?php
class AdminPartnerBenefitsView extends BaseView {
    protected $data = [];
    protected $controller;

    public function index() {
        $this->renderHead();
        $this->render_header();
        $this->render_standard_discounts();
        $this->render_special_offers();
        $this->render_advantages();
        $this->render_modals();
    }

    private function render_header() {
    ?>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">
                Benefits Management for <?= htmlspecialchars($this->data['partner']['name']) ?>
            </h1>
            <a href="/adminPartner" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                Back to Partners
            </a>
        </div>
    </div>
    <?php
    }

    private function render_standard_discounts() {
    ?>
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Standard Discounts</h2>
                <button onclick="document.getElementById('createDiscountModal').style.display='block'"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Add Discount
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Value</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['standardDiscounts'] as $discount): ?>
                        <tr>
                            <td class="px-6 py-4"><?= htmlspecialchars($discount['description']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($discount['discount_value']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($discount['discount_type']) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="editDiscount(<?= htmlspecialchars(json_encode($discount)) ?>)"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Edit
                                    </button>
                                    <form method="POST" action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/deleteDiscount"
                                          onsubmit="return confirm('Are you sure you want to delete this discount?')"
                                          class="inline">
                                        <input type="hidden" name="discount_id" value="<?= $discount['id'] ?>">
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
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

    private function render_special_offers() {
    ?>
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Special Offers</h2>
                <button onclick="document.getElementById('createOfferModal').style.display='block'"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Add Offer
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Value</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Period</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['specialOffers'] as $offer): ?>
                        <tr>
                            <td class="px-6 py-4"><?= htmlspecialchars($offer['description']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($offer['discount_value']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($offer['offer_type']) ?></td>
                            <td class="px-6 py-4">
                                <?= date('Y-m-d', strtotime($offer['start_date'])) ?> to 
                                <?= date('Y-m-d', strtotime($offer['end_date'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="editOffer(<?= htmlspecialchars(json_encode($offer)) ?>)"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Edit
                                    </button>
                                    <form method="POST" action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/deleteOffer"
                                          onsubmit="return confirm('Are you sure you want to delete this offer?')"
                                          class="inline">
                                        <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
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

    private function render_advantages() {
    ?>
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Advantages</h2>
                <button onclick="document.getElementById('createAdvantageModal').style.display='block'"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Add Advantage
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['advantages'] as $advantage): ?>
                        <tr>
                            <td class="px-6 py-4"><?= htmlspecialchars($advantage['description']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($advantage['advantage_type']) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="editAdvantage(<?= htmlspecialchars(json_encode($advantage)) ?>)"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Edit
                                    </button>
                                    <form method="POST" action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/deleteAdvantage"
                                          onsubmit="return confirm('Are you sure you want to delete this advantage?')"
                                          class="inline">
                                        <input type="hidden" name="advantage_id" value="<?= $advantage['id'] ?>">
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
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

    private function render_modals() {
        $this->render_discount_modals();
        $this->render_offer_modals();
        $this->render_advantage_modals();
    }

    private function render_discount_modals() {
    ?>
    <!-- Create Discount Modal -->
    <div id="createDiscountModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Discount</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/createDiscount" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                                <input type="number" name="discount_value" required step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                                <select name="discount_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): error_log(json_encode($type['membership_type_id'])); ?>
                                
                                <div class="flex items-center space-x-2 mb-2"> ```php
                                    <input type="checkbox" name="membership_types[<?= $type['membership_type_id'] ?>]" 
                                           id="discount_type_<?= $type['membership_type_id'] ?>"
                                           value="0"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="discount_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                    <input type="number" 
                                           name="membership_types[<?= $type['membership_type_id'] ?>]"
                                           placeholder="Bonus value"
                                           class="ml-2 w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                           step="0.01">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('createDiscountModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Create Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Discount Modal -->
    <div id="editDiscountModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Discount</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/updateDiscount" method="POST">
                        <input type="hidden" name="discount_id" id="edit_discount_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_discount_description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                                <input type="number" name="discount_value" id="edit_discount_value" required step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                                <select name="discount_type" id="edit_discount_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="membership_types[<?= $type['membership_type_id'] ?>]" 
                                           id="edit_discount_type_<?= $type['membership_type_id'] ?>"
                                           value="0"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="edit_discount_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                    <input type="number" 
                                           name="membership_types[<?= $type['membership_type_id'] ?>]"
                                           id="edit_discount_bonus_<?= $type['membership_type_id'] ?>"
                                           placeholder="Bonus value"
                                           class="ml-2 w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                           step="0.01">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('editDiscountModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Update Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

    private function render_offer_modals() {
    ?>
    <!-- Create Offer Modal -->
    <div id="createOfferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Special Offer</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/createOffer" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                                <input type="number" name="discount_value" required step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Offer Type</label>
                                <select name="offer_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="bogo">Buy One Get One</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="membership_types[<?= $type['membership_type_id'] ?>]" 
                                           id="offer_type_<?= $type['membership_type_id'] ?>"
                                           value="0"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="offer_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                    <input type="number" 
                                           name="membership_types[<?= $type['membership_type_id'] ?>]"
                                           placeholder="Bonus value"
                                           class="ml-2 w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                           step="0.01">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('createOfferModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Create Offer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Offer Modal -->
    <div id="editOfferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Special Offer</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/updateOffer" method="POST">
                        <input type="hidden" name="offer_id" id="edit_offer_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_offer_description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Discount Value</label>
                                <input type="number" name="discount_value" id="edit_offer_value" required step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Offer Type</label>
                                <select name="offer_type" id="edit_offer_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="bogo">Buy One Get One</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="edit_offer_start_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="edit_offer_end_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="membership_types[<?= $type['membership_type_id'] ?>]" 
                                           id="edit_offer_type_<?= $type['membership_type_id'] ?>"
                                           value="0"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="edit_offer_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                    <input type="number" 
                                           name="membership_types[<?= $type['membership_type_id'] ?>]"
                                           id="edit_offer_bonus_<?= $type['membership_type_id'] ?>"
                                           placeholder="Bonus value"
                                           class="ml-2 w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                           step="0.01">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('editOfferModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Update Offer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

    private function render_advantage_modals() {
    ?>
    <!-- Create Advantage Modal -->
    <div id="createAdvantageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Advantage</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/createAdvantage" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Advantage Type</label>
                                <select name="advantage_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="service">Service</option>
                                    <option value="product">Product</option>
                                    <option value="access">Access</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="membership_types[]" 
                                           value="<?= $type['membership_type_id'] ?>"
                                           id="advantage_type_<?= $type['membership_type_id'] ?>"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="advantage_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('createAdvantageModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Create Advantage
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Advantage Modal -->
    <div id="editAdvantageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Advantage</h3>
                    <form action="/adminPartnerBenefits/<?= $this->data['partner']['id'] ?>/updateAdvantage" method="POST">
                        <input type="hidden" name="advantage_id" id="edit_advantage_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="edit_advantage_description" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Advantage Type</label>
                                <select name="advantage_type" id="edit_advantage_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <option value="service">Service</option>
                                    <option value="product">Product</option>
                                    <option value="access">Access</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Membership Types</label>
                                <?php foreach ($this->data['membershipTypes'] as $type): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="membership_types[]" 
                                           value="<?= $type['membership_type_id'] ?>"
                                           id="edit_advantage_type_<?= $type['membership_type_id'] ?>"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="edit_advantage_type_<?= $type['membership_type_id'] ?>"><?= htmlspecialchars($type['name']) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('editAdvantageModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Update Advantage
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function editDiscount(discount) {
        document.getElementById('edit_discount_id').value = discount.id;
        document.getElementById('edit_discount_description').value = discount.description;
        document.getElementById('edit_discount_value').value = discount.discount_value;
        document.getElementById('edit_discount_type').value = discount.discount_type;
        
        // Reset all checkboxes and bonus values
        document.querySelectorAll('[id^="edit_discount_type_"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('[id^="edit_discount_bonus_"]').forEach(input => {
            input.value = '';
        });
        
        // Set eligibilities
        if (discount.eligibilities) {
            discount.eligibilities.forEach(eligibility => {
                const checkbox = document.getElementById(`edit_discount_type_${eligibility.membership_type_id}`);
                const bonusInput = document.getElementById(`edit_discount_bonus_${eligibility.membership_type_id}`);
                if (checkbox) checkbox.checked = true;
                if (bonusInput) bonusInput.value = eligibility.bonus_value;
            });
        }
        
        document.getElementById('editDiscountModal').style.display = 'block';
    }

    function editOffer(offer) {
        document.getElementById('edit_offer_id').value = offer.id;
        document.getElementById('edit_offer_description').value = offer.description;
        document.getElementById('edit_offer_value').value = offer.discount_value;
        document.getElementById('edit_offer_type').value = offer.offer_type;
        document.getElementById('edit_offer_start_date').value = offer.start_date.split(' ')[0];
        document.getElementById('edit_offer_end_date').value = offer.end_date.split(' ')[0];
        
        // Reset all checkboxes and bonus values
        document.querySelectorAll('[id^="edit_offer_type_"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.querySelectorAll('[id^="edit_offer_bonus_"]').forEach(input => {
            input.value = '';
        });
        
        // Set eligibilities
        if (offer.eligibilities) {
            offer.eligibilities.forEach(eligibility => {
                const checkbox = document.getElementById(`edit_offer_type_${eligibility.membership_type_id}`);
                const bonusInput = document.getElementById(`edit_offer_bonus_${eligibility.membership_type_id}`);
                if (checkbox) checkbox.checked = true;
                if (bonusInput) bonusInput.value = eligibility.bonus_value;
            });
        }
        
        document.getElementById('editOfferModal').style.display = 'block';
    }

    function editAdvantage(advantage) {
        document.getElementById('edit_advantage_id').value = advantage.id;
        document.getElementById('edit_advantage_description').value = advantage.description;
        document.getElementById('edit_advantage_type').value = advantage.advantage_type;
        
        // Reset all checkboxes
        document.querySelectorAll('[id^="edit_advantage_type_"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Set eligibilities
        if (advantage.eligibilities) {
            advantage.eligibilities.forEach(eligibility => {
                const checkbox = document.getElementById(`edit_advantage_type_${eligibility.membership_type_id}`);
                if (checkbox) checkbox.checked = true;
            });
        }
        
        document.getElementById('editAdvantageModal').style.display = 'block';
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
