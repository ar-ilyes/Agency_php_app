<?php

class AdminEventView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_statistics(); 
        $this->render_create_form();
        // render_edit_modal
        $this->render_edit_modal();
        $this->render_events_list();
    }
    
    private function render_create_form() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Event Management</h2>
                
                <form action="/adminEvent/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" 
                               name="title" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" 
                               name="location" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="datetime-local" 
                               name="date_start" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="datetime-local" 
                               name="date_end" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maximum Volunteers</label>
                        <input type="number" 
                               name="max_volunteers" 
                               required
                               min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" 
                                  required
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <button type="submit"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                            Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function render_events_list() {
        ?>
        <div class="container mx-auto px-4 pb-8">
            <?php foreach ($this->data['events'] as $event): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-semibold"><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($event['location']) ?></p>
                    </div>
                    <form method="POST" 
                          action="/adminEvent/delete"
                          onsubmit="return confirm('Are you sure you want to delete this event?')">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <button type="submit"
                                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                            Delete Event
                        </button>
                    </form>
                    <button onclick="editEvent(<?= htmlspecialchars(json_encode($event)) ?>)"
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors mr-2">
                        Edit Event
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Start Date</p>
                        <p><?= date('Y-m-d H:i', strtotime($event['date_start'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">End Date</p>
                        <p><?= date('Y-m-d H:i', strtotime($event['date_end'])) ?></p>
                    </div>
                </div>
                
                <p class="mb-4"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold">Volunteers (<?= count($event['volunteers']) ?>/<?= $event['max_volunteers'] ?>)</h4>
                    </div>
                    
                    <?php if (!empty($event['volunteers'])): ?>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-sm font-semibold text-gray-600">Name</th>
                                    <th class="text-left text-sm font-semibold text-gray-600">Email</th>
                                    <th class="text-left text-sm font-semibold text-gray-600">Registration Date</th>
                                    <th class="text-left text-sm font-semibold text-gray-600">Status</th>
                                    <th class="text-left text-sm font-semibold text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($event['volunteers'] as $volunteer): ?>
                                <tr>
                                    <td class="py-2"><?= htmlspecialchars($volunteer['first_name'] . ' ' . $volunteer['last_name']) ?></td>
                                    <td class="py-2"><?= htmlspecialchars($volunteer['email']) ?></td>
                                    <td class="py-2"><?= date('Y-m-d H:i', strtotime($volunteer['registration_date'])) ?></td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 text-sm rounded <?= $volunteer['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= ucfirst($volunteer['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-2">
                                        <?php if ($volunteer['status'] !== 'approved'): ?>
                                        <form method="POST" 
                                              action="/adminEvent/updateVolunteer"
                                              class="inline">
                                            <input type="hidden" name="volunteer_id" value="<?= $volunteer['id'] ?>">
                                            <input type="hidden" name="status" value="approved">
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
                    <?php else: ?>
                    <p class="text-gray-500">No volunteers yet</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    private function render_statistics() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Event Statistics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800">Total Active Events</h3>
                        <p class="text-3xl font-bold text-blue-600"><?= $this->data['stats']['total_events'] ?></p>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-green-800">Total Volunteers</h3>
                        <p class="text-3xl font-bold text-green-600"><?= $this->data['stats']['total_volunteers'] ?></p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-purple-800">Volunteer Status</h3>
                        <div class="space-y-2 mt-2">
                            <?php foreach ($this->data['stats']['volunteers_by_status'] as $status => $count): ?>
                            <div class="flex justify-between">
                                <span class="capitalize"><?= $status ?></span>
                                <span class="font-semibold"><?= $count ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_edit_modal() {
        ?>
        <div id="editEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Edit Event</h3>
                        <form action="/adminEvent/update" method="POST">
                            <input type="hidden" name="event_id" id="edit_event_id">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Same fields as create form but with id attributes -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="title" 
                                           id="edit_title"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" 
                                           name="location" 
                                           id="edit_location"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="datetime-local" 
                                           name="date_start" 
                                           id="edit_date_start"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="datetime-local" 
                                           name="date_end" 
                                           id="edit_date_end"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Maximum Volunteers</label>
                                    <input type="number" 
                                           name="max_volunteers" 
                                           id="edit_max_volunteers"
                                           required
                                           min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" 
                                              id="edit_description"
                                              required
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" 
                                        onclick="document.getElementById('editEventModal').style.display='none'"
                                        class="px-4 py-2 border rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Update Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        function editEvent(event) {
            document.getElementById('edit_event_id').value = event.id;
            document.getElementById('edit_title').value = event.title;
            document.getElementById('edit_description').value = event.description;
            document.getElementById('edit_location').value = event.location;
            document.getElementById('edit_max_volunteers').value = event.max_volunteers;
            
            // Format dates for datetime-local input
            const startDate = new Date(event.date_start);
            const endDate = new Date(event.date_end);
            
            document.getElementById('edit_date_start').value = startDate.toISOString().slice(0, 16);
            document.getElementById('edit_date_end').value = endDate.toISOString().slice(0, 16);
            
            document.getElementById('editEventModal').style.display = 'block';
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