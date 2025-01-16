<?php

class AdminAidRequestView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_statistics();
        $this->render_filters();
        $this->render_requests_table();
    }
    
    private function render_filters() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Aid Request Management</h2>
                
                <!-- Filters -->
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($this->data['filters']['search'] ?? '') ?>"
                               placeholder="Search by name..."
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="is_approved" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">All Status</option>
                            <option value="0" <?= isset($this->data['filters']['is_approved']) && $this->data['filters']['is_approved'] === false ? 'selected' : '' ?>>
                                Pending
                            </option>
                            <option value="1" <?= isset($this->data['filters']['is_approved']) && $this->data['filters']['is_approved'] === true ? 'selected' : '' ?>>
                                Approved
                            </option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Aid Type</label>
                        <select name="aid_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            <option value="">All Types</option>
                            <?php foreach ($this->data['aidTypes'] as $type): ?>
                            <option value="<?= $type['id'] ?>" 
                                    <?= ($this->data['filters']['aid_type'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="md:col-span-3">
                        <button type="submit" 
                                class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function render_requests_table() {
        ?>
        <div class="container mx-auto px-4 pb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Birth Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aid Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Document</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['aidRequests'] as $request): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($request['first_name'] . ' ' . $request['last_name']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= date('Y-m-d', strtotime($request['birth_date'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($request['aid_type']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($request['description']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($request['document_path']): ?>
                                <a href="<?= htmlspecialchars($request['document_path']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                    View Document
                                </a>
                                <?php else: ?>
                                <span class="text-gray-500">No Document</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($request['is_approved']): ?>
                                <span class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded">Approved</span>
                                <?php else: ?>
                                <span class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!$request['is_approved']): ?>
                                <form method="POST" 
                                      action="/adminAidRequest/approve"
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to approve this aid request?')">
                                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                        Approve
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
    
    private function render_statistics() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Aid Request Statistics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800">Total Requests</h3>
                        <p class="text-3xl font-bold text-blue-600"><?= $this->data['stats']['total_requests'] ?></p>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-yellow-800">Pending Requests</h3>
                        <p class="text-3xl font-bold text-yellow-600"><?= $this->data['stats']['pending_requests'] ?></p>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-green-800">Approved Requests</h3>
                        <p class="text-3xl font-bold text-green-600"><?= $this->data['stats']['approved_requests'] ?></p>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Requests by Type</h3>
                    <div class="bg-white rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aid Type</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Number of Requests</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Approved</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Pending</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($this->data['stats']['requests_by_type'] as $type): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= htmlspecialchars($type['name']) ?></td>
                                    <td class="px-6 py-4"><?= $type['total'] ?></td>
                                    <td class="px-6 py-4"><?= $type['approved'] ?></td>
                                    <td class="px-6 py-4"><?= $type['pending'] ?></td>
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
