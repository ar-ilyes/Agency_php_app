<?php

class NewsView extends BaseView {
    protected $data = [];
    protected $controller;
    
    public function index() {
        $this->renderHead();
        $this->render_news_section();
    }
    
    private function render_news_section() {
        ?>
        <div class="min-h-screen bg-gray-100">
            <div class="container mx-auto px-4 py-8">
                <h1 class="text-4xl font-bold mb-8">Latest News & Events</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($this->data['news'] as $item): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     class="w-full h-48 object-cover">
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h2 class="text-xl font-bold"><?= htmlspecialchars($item['title']) ?></h2>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                        <?= isset($item['date_start']) ? 'Event' : 'Announcement' ?>
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">
                                    <?= htmlspecialchars(substr($item['description'], 0, 150)) ?>...
                                </p>
                                
                                <?php if (isset($item['date_start'])): ?>
                                    <div class="mb-4">
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <?= date('F j, Y', strtotime($item['date_start'])) ?>
                                            <?php if (isset($item['location'])): ?>
                                                <svg class="w-4 h-4 ml-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <?= htmlspecialchars($item['location']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-gray-500">
                                        Posted on <?= date('F j, Y', strtotime($item['created_at'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
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