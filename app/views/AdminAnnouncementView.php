<?php

class AdminAnnouncementView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_header();
        $this->render_announcements_table();
        $this->render_create_modal();
        $this->render_edit_modal();
    }
    
    private function render_header() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Announcement Management</h2>
                
                <!-- Add Announcement Button -->
                <button onclick="document.getElementById('createAnnouncementModal').style.display='block'" 
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                    Add New Announcement
                </button>
            </div>
        </div>
        <?php
    }
    
    private function render_announcements_table() {
        ?>
        <div class="container mx-auto px-4 pb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Start Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">End Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($this->data['announcements'] as $announcement): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?php if ($announcement['image']): ?>
                                <img src="<?= htmlspecialchars($announcement['image']) ?>" 
                                     alt="<?= htmlspecialchars($announcement['title']) ?>"
                                     class="h-10 w-10 object-cover rounded">
                                <?php else: ?>
                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($announcement['title']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars(substr($announcement['description'], 0, 100)) ?>...</td>
                            <td class="px-6 py-4"><?= htmlspecialchars($announcement['start_date']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($announcement['end_date']) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="editAnnouncement(<?= htmlspecialchars(json_encode($announcement)) ?>)"
                                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Edit
                                    </button>
                                    <form method="POST" 
                                          action="/adminAnnouncement/delete" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                        <input type="hidden" name="announcement_id" value="<?= $announcement['id'] ?>">
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
        <?php
    }
    
    private function render_create_modal() {
        ?>
        <div id="createAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Add New Announcement</h3>
                        <form action="/adminAnnouncement/create" method="POST" enctype="multipart/form-data">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="title" 
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" 
                                              required
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" 
                                           name="start_date" 
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" 
                                           name="end_date" 
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" 
                                           name="image" 
                                           accept="image/*"
                                           class="mt-1 block w-full">
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" 
                                        onclick="document.getElementById('createAnnouncementModal').style.display='none'"
                                        class="px-4 py-2 border rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Create Announcement
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
        <div id="editAnnouncementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Edit Announcement</h3>
                        <form action="/adminAnnouncement/update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="announcement_id" id="edit_announcement_id">
                            <input type="hidden" name="current_image" id="edit_current_image">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="title" 
                                           id="edit_title"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" 
                                              id="edit_description"
                                              required
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" 
                                           name="start_date" 
                                           id="edit_start_date"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" 
                                           name="end_date" 
                                           id="edit_end_date"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" 
                                           name="image" 
                                           accept="image/*"
                                           class="mt-1 block w-full">
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" 
                                        onclick="document.getElementById('editAnnouncementModal').style.display='none'"
                                        class="px-4 py-2 border rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Update Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function editAnnouncement(announcement) {
                document.getElementById('edit_announcement_id').value = announcement.id;
                document.getElementById('edit_title').value = announcement.title;
                document.getElementById('edit_description').value = announcement.description;
                document.getElementById('edit_start_date').value = announcement.start_date;
                document.getElementById('edit_end_date').value = announcement.end_date;
                document.getElementById('edit_current_image').value = announcement.image || '';
                document.getElementById('editAnnouncementModal').style.display = 'block';
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