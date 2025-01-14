<?php
class PartnersView {
    private Partners $controller;
    
    public function __construct()
    {
        $this->controller = new Partners();
    }
    
    public function afficher_site($partners, $categories, $cities, $selected_category, $selected_city) {
        $this->render_header();
        $this->render_filters($categories, $cities, $selected_category, $selected_city);
        $this->render_partners_grid($partners);
        $this->render_footer();
    }
    
    private function render_header() {
    ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Nos Partenaires</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-50 min-h-screen">
            <?php include 'header.php'; ?>
            
            <main class="container mx-auto px-4 py-8">
                <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Nos Partenaires</h1>
    <?php
    }
    
    private function render_filters($categories, $cities, $selected_category, $selected_city) {
    ?>
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form method="get" class="flex flex-col md:flex-row justify-center items-center gap-4">
                <select name="categorie" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full md:w-64">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" 
                                <?= ($selected_category == $cat) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="ville" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 w-full md:w-64">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city) ?>" 
                                <?= ($selected_city == $city) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" 
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 w-full md:w-auto">
                    Filtrer
                </button>
            </form>
        </div>
    <?php
    }
    
    private function render_partners_grid($partners) {
        ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if (empty($partners)): ?>
                    <div class="col-span-full text-center text-gray-600 text-lg py-8">
                        Aucun partenaire trouvé.
                    </div>
                <?php else: ?>
                    <?php foreach ($partners as $partner): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-2 text-gray-800">
                                    <?= htmlspecialchars($partner['name']) ?>
                                </h2>
                                <div class="space-y-2 mb-4">
                                    <p class="text-gray-600">
                                        <span class="font-medium">Catégorie:</span> 
                                        <?= htmlspecialchars($partner['category']) ?>
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Ville:</span> 
                                        <?= htmlspecialchars($partner['city']) ?>
                                    </p>
                                </div>
                                <a href="/partner/details/<?= $partner['id'] ?>" 
                                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300">
                                    Plus de détails
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php
        }
    
    private function render_footer() {
    ?>
            </main>
            <?php include 'footer.php'; ?>
        </body>
        </html>
    <?php
    }
}