<?php

class RegistrationView extends BaseView {
    private Register $controller;

    public function __construct() {
        $this->controller = new Register();
    }

    public function afficher_site() {
        $this->renderHead();
        $this->afficher_form_container();
        $this->addScript();
    }

    protected function afficher_form_container() {
        ?>
        <div class="max-w-2xl mx-auto p-6 my-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <?php
                $this->afficher_form_header();
                $this->afficher_registration_form();
                ?>
            </div>
        </div>
        <?php
    }

    protected function afficher_form_header() {
        ?>
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Inscription des Membres</h1>
            <p class="mt-2 text-gray-600">Rejoignez notre association en remplissant le formulaire ci-dessous</p>
        </div>
        <?php
    }

    protected function afficher_registration_form() {
        ?>
        <form action="/members/register" method="post" enctype="multipart/form-data" class="space-y-6">
            <?php
            $this->afficher_personal_info_fields();
            $this->afficher_address_fields();
            $this->afficher_membership_field();
            $this->afficher_document_upload_fields();
            $this->afficher_submit_button();
            ?>
        </form>
        <?php
    }

    protected function afficher_personal_info_fields() {
        ?>
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Informations Personnelles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom:</label>
                    <input type="text" id="first_name" name="first_name" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom:</label>
                    <input type="text" id="last_name" name="last_name" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="md:col-span-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" required 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>
        <?php
    }

    protected function afficher_address_fields() {
        ?>
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Adresse</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse:</label>
                    <input type="text" id="address" name="address" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">Ville:</label>
                    <input type="text" id="city" name="city" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>
        <?php
    }

    protected function afficher_membership_field() {
        ?>
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Type d'Adhésion</h2>
            <div>
                <select id="membership_type_id" name="membership_type_id" required 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Choisir un type d'adhésion</option>
                    <?php foreach ($this->controller->get_membership_types() as $membership_type): ?>
                        <option value="<?php echo htmlspecialchars($membership_type['membership_type_id']); ?>">
                            <?php echo htmlspecialchars($membership_type['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php
    }

    protected function afficher_document_upload_fields() {
        ?>
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800">Documents Requis</h2>
            <div class="space-y-4">
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo: (doit obligatoirement être png)</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label for="id_document" class="block text-sm font-medium text-gray-700">Pièce d'identité:</label>
                    <input type="file" id="id_document" name="id_document" accept="application/pdf" required 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label for="payment_receipt" class="block text-sm font-medium text-gray-700">Reçu de paiement:</label>
                    <input type="file" id="payment_receipt" name="payment_receipt" accept="application/pdf" required 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
        </div>
        <?php
    }

    protected function afficher_submit_button() {
        ?>
        <div class="pt-4">
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-lg font-semibold">
                S'inscrire
            </button>
        </div>
        <?php
    }

    public function register_member() {
        $member_data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'membership_type_id' => intval($_POST['membership_type_id']),
            'photo' => $_FILES['photo'],
            'id_document' => $_FILES['id_document'],
            'payment_receipt' => $_FILES['payment_receipt']
        ];

        return $this->controller->register_member($member_data);
    }

    protected function addScript() {
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.querySelector("form");
                form.addEventListener("submit", function(event) {
                    event.preventDefault();  

                    const formData = new FormData(form);  

                    fetch('/register', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Registration successful!");
                            window.location.href = "/auth";
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
            });
        </script>
        <?php
    }
}