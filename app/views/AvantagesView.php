<?php
class AvantagesView extends BaseView
{
    public function index()
    {
        $this->renderHead();
        $this->render_avantages_section();
        $this->render_footer();
    }

    private function render_avantages_section()
    {
    ?>
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Liste des Avantages</h2>
                    
                    <!-- Filter Section -->
                    <form class="flex gap-4">
                        <input type="text" name="partenaire" placeholder="Filtrer par partenaire" 
                               class="border px-4 py-2 rounded focus:outline-blue-600" />
                        <input type="text" name="ville" placeholder="Filtrer par ville" 
                               class="border px-4 py-2 rounded focus:outline-blue-600" />
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Filtrer
                        </button>
                    </form>
                </div>
                
                <!-- Avantages Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Partenaire</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Ville</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Avantage</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            $types = ['Réduction', 'Offre spéciale', 'Accès VIP', 'Cadeau'];
                            for ($i = 1; $i <= 5; $i++): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">Partenaire <?= $i ?></td>
                                <td class="px-6 py-4">Ville <?= $i ?></td>
                                <td class="px-6 py-4">Avantage <?= $i ?></td>
                                <td class="px-6 py-4"><?= $types[array_rand($types)] ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-center gap-2 mt-4">
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Précédent</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">1</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">2</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-50">Suivant</button>
                </div>
            </div>
        </div>
    <?php
    }
}
?>
