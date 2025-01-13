<?php
class AidTypesView extends BaseView {
    public function afficher_site($data) {
        $this->renderHead();
        $this->afficher_title();
        $this->afficher_aid_types($data['aid_types']);
        $this->renderFooter();
    }
    
    private function afficher_title() {
        ?>
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900">Available Aid Types</h1>
            <p class="mt-2 text-gray-600">Explore different types of aid available</p>
        </div>
        <?php
    }
    
    private function afficher_aid_types($aid_types) {
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($aid_types as $aid_type): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        <?= htmlspecialchars($aid_type['name']) ?>
                    </h2>
                    <p class="text-gray-600">
                        <?= htmlspecialchars($aid_type['description']) ?>
                    </p>
                    <div class="mt-4 text-sm text-gray-500">
                        Added: <?= date('d/m/Y', strtotime($aid_type['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
