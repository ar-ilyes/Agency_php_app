<?php
class NotificationView extends BaseView {
    protected $data = [];
    protected $controller;

    public function index() {
        $this->renderHead();
        $this->render_notifications();
    }

    private function render_notifications() {
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Notifications</h2>

                <?php if (empty($this->data['notifications'])): ?>
                    <p class="text-gray-500 text-center py-8">No notifications yet</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($this->data['notifications'] as $notification): ?>
                            <div class="p-4 rounded-lg <?= $notification['is_read'] ? 'bg-gray-50' : 'bg-blue-50' ?>">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-lg <?= $notification['is_read'] ? 'text-gray-800' : 'text-blue-800' ?>">
                                            <?= htmlspecialchars($notification['title']) ?>
                                        </h3>
                                        <p class="text-gray-600 mt-1">
                                            <?= htmlspecialchars($notification['description']) ?>
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            <?= date('F j, Y g:i A', strtotime($notification['created_at'])) ?>
                                        </p>
                                    </div>
                                    
                                    <?php if (!$notification['is_read']): ?>
                                        <form method="POST" action="/notification/markAsRead">
                                            <input type="hidden" name="notification_id" value="<?= $notification['id'] ?>">
                                            <button type="submit" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                Mark as read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
