<?php
class AidRequestView extends BaseView {
    private AidRequest $controller;
    
    public function __construct() {
        $this->controller = new AidRequest();
    }

    public function index($aid_types = []) {
        $aid_types_with_desc = array_map(function($type) {
            $type['description'] = $this->controller->get_aid_type_description($type['id']);
            return $type;
        }, $aid_types);
        
        $this->renderHead();
        $this->render_form($aid_types_with_desc);
        $this->renderFooter();
    }

    public function success() {
        $this->renderHead();
        $this->render_success_message();
        $this->renderFooter();
    }

    public function error() {
        $this->renderHead();
        $this->render_error_message();
        $this->renderFooter();
    }

    private function render_form($aid_types) {
    ?>
        <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8">
                    <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">
                        Demande d'aide
                    </h2>
                    
                    <form action="/aidRequest" method="post" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                Prénom
                            </label>
                            <input type="text" name="first_name" id="first_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">
                                Nom
                            </label>
                            <input type="text" name="last_name" id="last_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">
                                Date de naissance
                            </label>
                            <input type="date" name="birth_date" id="birth_date" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="aid_type" class="block text-sm font-medium text-gray-700">
                                Type d'aide
                            </label>
                            <select name="aid_type" id="aid_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionnez un type</option>
                                <?php foreach ($aid_types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type['id']); ?>" 
                                            data-description="<?php echo htmlspecialchars($type['description']); ?>">
                                        <?php echo htmlspecialchars($type['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="documentRequirements" class="hidden">
                            <div class="mt-2 p-4 bg-gray-50 rounded-md">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Documents requis :</h3>
                                <pre id="requirementsList" class="text-sm text-gray-600 whitespace-pre-wrap"></pre>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700">
                                Dossier (format ZIP)
                            </label>
                            <input type="file" name="document" id="document" accept=".zip" required
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Soumettre la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector("form");
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                fetch('/aidRequest', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Registration successful!");
                            // Optionally, redirect or reset the form
                            form.reset();
                        } else {
                            alert("Error during registration: " + data.error);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Something went wrong!");
                    });
            });

            const aidTypeSelect = document.getElementById('aid_type');
            const documentRequirements = document.getElementById('documentRequirements');
            const requirementsList = document.getElementById('requirementsList');

            aidTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const description = selectedOption.getAttribute('data-description');
                
                if (description) {
                    requirementsList.textContent = description;
                    documentRequirements.classList.remove('hidden');
                } else {
                    documentRequirements.classList.add('hidden');
                }
            });

        });
        </script>
    <?php
    }

    private function render_success_message() {
    ?>
        <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8 text-center">
                    <h2 class="text-2xl font-bold text-green-600 mb-4">
                        Demande envoyée avec succès
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Votre demande d'aide a été enregistrée. Nous vous contacterons bientôt.
                    </p>
                    <a href="/" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_error_message() {
    ?>
        <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8 text-center">
                    <h2 class="text-2xl font-bold text-red-600 mb-4">
                        Erreur
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Une erreur est survenue lors de l'envoi de votre demande. Veuillez réessayer.
                    </p>
                    <a href="/aid-request" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Retour au formulaire
                    </a>
                </div>
            </div>
        </div>
    <?php
    }
}