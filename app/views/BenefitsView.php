<?php
class BenefitsView {
    public function afficher_site($data) {
        $this->afficher_header();
        $this->afficher_membership_info($data['membership_type']);
        $this->afficher_standard_discounts($data['standard_discounts']);
        $this->afficher_special_offers($data['special_offers']);
        $this->afficher_advantages($data['advantages']);
        $this->afficher_footer();
    }
    
    private function afficher_header() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Member Benefits</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container py-5">
        <?php
    }
    
    private function afficher_membership_info($membership_type) {
        ?>
        <div class="text-center mb-5">
            <h1>Your Member Benefits</h1>
            <h3 class="text-muted"><?= htmlspecialchars($membership_type['type_name']) ?> Membership</h3>
        </div>
        <?php
    }
    
    private function afficher_standard_discounts($discounts) {
        ?>
        <section class="mb-5">
            <h2 class="mb-4">Standard Discounts</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($discounts as $discount): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($discount['partner_name']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($discount['partner_category']) ?></h6>
                                <p class="card-text"><?= htmlspecialchars($discount['description']) ?></p>
                                <div class="badge bg-primary">
                                    <?= htmlspecialchars($discount['discount_value']) ?>% 
                                    <?php if ($discount['bonus_value'] > 0): ?>
                                        + <?= htmlspecialchars($discount['bonus_value']) ?>% bonus
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
    
    private function afficher_special_offers($offers) {
        ?>
        <section class="mb-5">
            <h2 class="mb-4">Special Offers</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($offers as $offer): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($offer['partner_name']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($offer['partner_category']) ?></h6>
                                <p class="card-text"><?= htmlspecialchars($offer['description']) ?></p>
                                <div class="badge bg-success">
                                    <?= htmlspecialchars($offer['discount_value']) ?>% 
                                    <?php if ($offer['bonus_value'] > 0): ?>
                                        + <?= htmlspecialchars($offer['bonus_value']) ?>% bonus
                                    <?php endif; ?>
                                </div>
                                <p class="mt-2 small">
                                    Valid until: <?= date('d/m/Y', strtotime($offer['end_date'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
    
    private function afficher_advantages($advantages) {
        ?>
        <section class="mb-5">
            <h2 class="mb-4">Additional Advantages</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($advantages as $advantage): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($advantage['partner_name']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($advantage['partner_category']) ?></h6>
                                <p class="card-text"><?= htmlspecialchars($advantage['description']) ?></p>
                                <div class="badge bg-info">
                                    <?= htmlspecialchars($advantage['advantage_type']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
    
    private function afficher_footer() {
        ?>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}
