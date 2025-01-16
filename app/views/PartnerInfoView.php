<?php
class PartnerInfoView extends BaseView {
    protected $data = [];
    protected $controller;

    public function index() {
        $this->renderHead();
        $this->render_partner_info();
    }

    private function render_partner_info() {
        $partner = $this->data['partner'];
        ?>
        <div class="min-h-screen bg-gray-100 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Partner Header -->
                    <div class="relative h-48 bg-blue-600">
                        <div class="absolute bottom-0 left-0 right-0 px-6 py-4 bg-gradient-to-t from-black/60">
                            <h1 class="text-3xl font-bold text-white">
                                <?= htmlspecialchars($partner['name']) ?>
                            </h1>
                        </div>
                    </div>

                    <!-- Partner Logo -->
                    <div class="relative px-6">
                        <div class="absolute -top-16">
                            <?php if ($partner['logo']): ?>
                                <img 
                                    src="/<?= htmlspecialchars($partner['logo']) ?>" 
                                    alt="<?= htmlspecialchars($partner['name']) ?> logo"
                                    class="w-32 h-32 rounded-lg border-4 border-white shadow-lg object-cover bg-white"
                                >
                            <?php else: ?>
                                <div class="w-32 h-32 rounded-lg border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-xl">No Logo</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Partner Details -->
                    <div class="px-6 pt-20 pb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Location</h3>
                                <p class="mt-1 text-lg text-gray-900">
                                    <?= htmlspecialchars($partner['city']) ?>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                <p class="mt-1 text-lg text-gray-900">
                                    <?= htmlspecialchars($partner['category']) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Partner ID (for debugging or admin purposes) -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                Partner ID: <?= htmlspecialchars($partner['id']) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="/partners" 
                               class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                                Back to Partners
                            </a>
                        </div>
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
