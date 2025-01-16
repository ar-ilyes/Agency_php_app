<?php 
class PartnerSelectView extends BaseView {
    public function index() {
        $this->renderHead();
        $this->render_partner_selection();
    }

    private function render_partner_selection() {
        $partners = $this->data['partners'];
        $currentFavorites = $this->data['currentFavorites'];
        $currentFavoriteIds = array_column($currentFavorites, 'partner_id');
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Select Favorite Partners</h2>
                
                <form action="/partnerSelect/save" method="POST" id="partnerSelectForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($partners as $partner): ?>
                            <div class="flex items-center space-x-3 p-4 border rounded">
                                <input type="checkbox" 
                                       id="partner_<?= htmlspecialchars($partner['id']) ?>"
                                       name="selected_partners[]" 
                                       value="<?= htmlspecialchars($partner['id']) ?>"
                                       <?= in_array($partner['id'], $currentFavoriteIds) ? 'checked' : '' ?>>
                                <label for="partner_<?= htmlspecialchars($partner['id']) ?>">
                                    <?= htmlspecialchars($partner['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="validationMessage" class="mt-4 text-red-600 hidden"></div>
                    
                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="/member" class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Apply Changes
                        </button>
                    </div>
                </form>
                <script>
                document.getElementById('partnerSelectForm').addEventListener('submit', function(e) {
                    e.preventDefault(); 
                    
                    const checkboxes = this.querySelectorAll('input[name="selected_partners[]"]:checked');
                    const validationMessage = document.getElementById('validationMessage');
                    
                    if (checkboxes.length === 0) {
                        validationMessage.textContent = 'Please select at least one partner.';
                        validationMessage.classList.remove('hidden');
                        return false;
                    }

                    const selectedPartners = Array.from(checkboxes).map(cb => cb.value);

                    fetch('/partnerSelect/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            selected_partners: selectedPartners
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/member'; 
                        } else {
                            validationMessage.textContent = data.message || 'An error occurred while saving favorites.';
                            validationMessage.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        validationMessage.textContent = 'An error occurred while saving favorites.';
                        validationMessage.classList.remove('hidden');
                        console.error('Error:', error);
                    });
                });
                </script>
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
