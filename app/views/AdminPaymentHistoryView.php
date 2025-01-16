<?php
class AdminPaymentHistoryView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_statistics();
        $this->render_filters();
        $this->render_payments_table();
    }
    
    private function render_filters() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Payment History Management</h2>
                
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
                        <label class="block text-sm font-medium text-gray-700">Payment Type</label>
                        <select name="payment_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">All Types</option>
                            <option value="registration" <?= ($this->data['filters']['payment_type'] ?? '') === 'registration' ? 'selected' : '' ?>>
                                Registration
                            </option>
                            <!-- Add other payment types as needed -->
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" 
                                   name="start_date" 
                                   value="<?= htmlspecialchars($this->data['filters']['start_date'] ?? '') ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <input type="date" 
                                   name="end_date" 
                                   value="<?= htmlspecialchars($this->data['filters']['end_date'] ?? '') ?>"
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
    
    private function render_payments_table() {
        ?>
        <div class="container mx-auto px-4 pb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Member</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Receipt</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['payments'] as $payment): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium"><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($payment['email']) ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded">
                                    <?= ucfirst(htmlspecialchars($payment['payment_type'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($payment['payment_receipt']): ?>
                                <a href="<?= htmlspecialchars($payment['payment_receipt']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    View Receipt
                                </a>
                                <?php else: ?>
                                <span class="text-gray-500">No Receipt</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4"><?= date('Y-m-d H:i', strtotime($payment['payment_date'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function render_statistics() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Payment Statistics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800">Total Payments</h3>
                        <p class="text-3xl font-bold text-blue-600"><?= $this->data['stats']['total_payments'] ?></p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-purple-800">Payments by Type</h3>
                        <div class="space-y-2 mt-2">
                            <?php foreach ($this->data['stats']['payments_by_type'] as $type => $count): ?>
                            <div class="flex justify-between">
                                <span><?= ucfirst($type) ?></span>
                                <span class="font-semibold"><?= $count ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Monthly Payment Breakdown</h3>
                    <div class="bg-white rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Month</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Payment Type</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Count</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($this->data['stats']['monthly_payments'] as $payment): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= date('F Y', strtotime($payment['month'] . '-01')) ?></td>
                                    <td class="px-6 py-4"><?= ucfirst($payment['payment_type']) ?></td>
                                    <td class="px-6 py-4"><?= $payment['type_count'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
