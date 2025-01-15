<?php
class AdminPartnerView extends BaseView {
    protected $data = [];
    protected $controller;

    public function index() {
        $this->renderHead();
        $this->render_filters();
        $this->render_partners_table();
        $this->render_create_modal();
        $this->render_edit_modal();
    }

    private function render_filters() {
    ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">Partner Management</h2>
            
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
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <select name="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
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
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All Categories</option>
                        <?php foreach ($this->data['categories'] as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"
                                <?= ($this->data['filters']['category'] ?? '') === $category ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
            
            <!-- Add Partner Button -->
            <button onclick="document.getElementById('createPartnerModal').style.display='block'" 
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                Add New Partner
            </button>
        </div>
    </div>
    <?php
    }

    private function render_partners_table() {
    ?>
    <div class="container mx-auto px-4 pb-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Logo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">City</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Category</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($this->data['partners'] as $partner): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <?php if ($partner['logo']): ?>
                            <img src="<?= htmlspecialchars($partner['logo']) ?>" 
                                 alt="<?= htmlspecialchars($partner['name']) ?>" 
                                 class="h-10 w-10 object-cover rounded">
                            <?php else: ?>
                            <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-500">No Logo</span>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($partner['name']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($partner['city']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($partner['category']) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button onclick="editPartner(<?= htmlspecialchars(json_encode($partner)) ?>)"
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    Edit
                                </button>
                                <form method="POST" action="/adminPartner/delete" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this partner?')">
                                    <input type="hidden" name="partner_id" value="<?= $partner['id'] ?>">
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                        Delete
                                    </button>
                                </form>
                                <a href="/adminPartnerBenefits/<?= $partner['id'] ?>"
                                class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors">
                                    Benefits
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    }

    private function render_create_modal() {
    ?>
    <div id="createPartnerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Add New Partner</h3>
                    <form action="/adminPartner/create" method="POST" enctype="multipart/form-data">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <select name="city" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <?php foreach ($this->data['cities'] as $city): ?>
                                    <option value="<?= htmlspecialchars($city) ?>">
                                        <?= htmlspecialchars($city) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <?php foreach ($this->data['categories'] as $category): ?>
                                    <option value="<?= htmlspecialchars($category) ?>">
                                        <?= htmlspecialchars($category) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" name="logo" accept="image/*"
                                        class="mt-1 block w-full">
                            </div>

                            //add email and password
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('createPartnerModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Create Partner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

    private function render_edit_modal() {
    ?>
    <div id="editPartnerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Edit Partner</h3>
                    <form action="/adminPartner/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="partner_id" id="edit_partner_id">
                        <input type="hidden" name="current_logo" id="edit_current_logo">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="edit_name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <select name="city" id="edit_city" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <?php foreach ($this->data['cities'] as $city): ?>
                                    <option value="<?= htmlspecialchars($city) ?>">
                                        <?= htmlspecialchars($city) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="edit_category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <?php foreach ($this->data['categories'] as $category): ?>
                                    <option value="<?= htmlspecialchars($category) ?>">
                                        <?= htmlspecialchars($category) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" name="logo" accept="image/*"
                                       class="mt-1 block w-full">
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="document.getElementById('editPartnerModal').style.display='none'"
                                    class="px-4 py-2 border rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Update Partner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function editPartner(partner) {
        document.getElementById('edit_partner_id').value = partner.id;
        document.getElementById('edit_name').value = partner.name;
        document.getElementById('edit_city').value = partner.city;
        document.getElementById('edit_category').value = partner.category;
        document.getElementById('edit_current_logo').value = partner.logo || '';
        document.getElementById('editPartnerModal').style.display = 'block';
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
