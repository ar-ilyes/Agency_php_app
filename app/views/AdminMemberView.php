<?php
class AdminMemberView extends BaseView {
    protected $data = [];
    protected $controller;

    public function index() {
        $this->renderHead();
        $this->render_filters();
        $this->render_members_table();
    }

    private function render_filters() {
    ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">Member Management</h2>
            
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" 
                           name="search" 
                           value="<?= htmlspecialchars($this->data['filters']['search'] ?? '') ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                           placeholder="Name or Email">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Approval Status</label>
                    <select name="is_approved" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All</option>
                        <option value="1" <?= (isset($this->data['filters']['is_approved']) && $this->data['filters']['is_approved'] === true) ? 'selected' : '' ?>>
                            Approved
                        </option>
                        <option value="0" <?= (isset($this->data['filters']['is_approved']) && $this->data['filters']['is_approved'] === false) ? 'selected' : '' ?>>
                            Pending
                        </option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <select name="city" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All Cities</option>
                        <?php foreach ($this->data['cities'] as $city): ?>
                        <option value="<?= htmlspecialchars($city) ?>" 
                                <?= ($this->data['filters']['city'] ?? '') === $city ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Membership Type</label>
                    <select name="membership_type_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All Types</option>
                        <?php foreach ($this->data['membership_types'] as $type): ?>
                        <option value="<?= htmlspecialchars($type['membership_type_id']) ?>"
                                <?= ($this->data['filters']['membership_type_id'] ?? '') == $type['membership_type_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date From</label>
                    <input type="date" 
                           name="date_from" 
                           value="<?= htmlspecialchars($this->data['filters']['date_from'] ?? '') ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date To</label>
                    <input type="date" 
                           name="date_to" 
                           value="<?= htmlspecialchars($this->data['filters']['date_to'] ?? '') ?>"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>
                
                <div class="col-span-full flex justify-end">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
    }

    private function render_members_table() {
    ?>
    <div class="container mx-auto px-4 pb-8 overflow-x-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">City</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Membership Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Inscription Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Photo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">ID Document</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Payment Receipt</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($this->data['members'] as $member): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($member['email']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($member['city']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($member['membership_type_name']) ?></td>
                        <td class="px-6 py-4">
                            <?= date('Y-m-d', strtotime($member['inscription_date'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($member['photo']): ?>
                                <a href="<?= htmlspecialchars($member['photo']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    View Photo
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400">No photo</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($member['id_document']): ?>
                                <a href="<?= htmlspecialchars($member['id_document']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View ID
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400">No document</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($member['payment_receipt']): ?>
                                <a href="<?= htmlspecialchars($member['payment_receipt']) ?>" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View Receipt
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400">No receipt</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($member['is_approved']): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    Approved
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                    Pending
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if (!$member['is_approved']): ?>
                            <form method="POST" action="/adminMember/approve" class="inline">
                                <input type="hidden" name="member_id" value="<?= $member['member_id'] ?>">
                                <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors mb-2">
                                    Approve
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="/adminMember/delete" class="inline">
                                <input type="hidden" name="member_id" value="<?= $member['member_id'] ?>">
                                <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                    delete
                                </button>
                            </form>
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
