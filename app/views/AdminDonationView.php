<?php

class AdminDonationView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_filters();
        $this->render_donations_table();
    }
    
    private function render_filters() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Donation Management</h2>
                
                <!-- Filters -->
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($this->data['filters']['search'] ?? '') ?>"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="is_validated" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">All Status</option>
                            <option value="0" <?= isset($this->data['filters']['is_validated']) && $this->data['filters']['is_validated'] === false ? 'selected' : '' ?>>
                                Pending
                            </option>
                            <option value="1" <?= isset($this->data['filters']['is_validated']) && $this->data['filters']['is_validated'] === true ? 'selected' : '' ?>>
                                Validated
                            </option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" 
                                   name="min_amount" 
                                   placeholder="Min"
                                   value="<?= htmlspecialchars($this->data['filters']['min_amount'] ?? '') ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <input type="number" 
                                   name="max_amount" 
                                   placeholder="Max"
                                   value="<?= htmlspecialchars($this->data['filters']['max_amount'] ?? '') ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function render_donations_table() {
        ?>
        <div class="container mx-auto px-4 pb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Member</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Receipt</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['donations'] as $donation): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium"><?= htmlspecialchars($donation['first_name'] . ' ' . $donation['last_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($donation['email']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">$<?= number_format($donation['amount'], 2) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($donation['payment_receipt']): ?>
                                <a href="<?= htmlspecialchars($donation['payment_receipt']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    View Receipt
                                </a>
                                <?php else: ?>
                                <span class="text-gray-500">No Receipt</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4"><?= date('Y-m-d H:i', strtotime($donation['donation_date'])) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($donation['is_validated']): ?>
                                <span class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded">Validated</span>
                                <?php else: ?>
                                <span class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!$donation['is_validated']): ?>
                                <form method="POST" 
                                      action="/adminDonation/validate"
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to validate this donation?')">
                                    <input type="hidden" name="donation_id" value="<?= $donation['id'] ?>">
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                        Validate
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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