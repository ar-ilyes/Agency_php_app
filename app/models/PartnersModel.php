<?php

class PartnersModel {
    private $partners = [
        [
            'id' => 1,
            'nom' => 'Hôtel Grand Paris',
            'categorie' => 'Hôtels',
            'ville' => 'Paris',
            'remise' => 15,
            'adresse' => '12 Avenue des Champs-Élysées',
            'telephone' => '01 23 45 67 89',
            'email' => 'contact@hotelgrandparis.fr',
            'description' => 'Un hôtel de luxe au cœur de Paris, offrant un service personnalisé et des chambres élégantes.'
        ],
        [
            'id' => 2,
            'nom' => 'Restaurant Le Gourmet',
            'categorie' => 'Restaurants',
            'ville' => 'Lyon',
            'remise' => 10,
            'adresse' => '5 Rue de la République',
            'telephone' => '04 78 29 30 31',
            'email' => 'contact@legourmet.fr',
            'description' => 'Un restaurant gastronomique proposant une cuisine française raffinée.'
        ],
        [
            'id' => 3,
            'nom' => 'Spa Relaxation',
            'categorie' => 'Spas',
            'ville' => 'Nice',
            'remise' => 20,
            'adresse' => '8 Promenade des Anglais',
            'telephone' => '04 93 82 82 82',
            'email' => 'contact@sparelaxation.fr',
            'description' => 'Un spa offrant une gamme complète de soins de bien-être et de relaxation.'
        ],
        [
            'id' => 4,
            'nom' => 'Boutique Chic',
            'categorie' => 'Boutiques',
            'ville' => 'Marseille',
            'remise' => 5,
            'adresse' => '10 Rue Saint-Ferréol',
            'telephone' => '04 91 54 54 54',
            'email' => 'contact@boutiquechic.fr',
            'description' => 'Une boutique de mode proposant des vêtements et accessoires tendance.'
        ],
        [
            'id' => 5,
            'nom' => 'Cinéma Lumière',
            'categorie' => 'Cinémas',
            'ville' => 'Toulouse',
            'remise' => 8,
            'adresse' => '15 Allée Jean Jaurès',
            'telephone' => '05 61 21 21 21',
            'email' => 'contact@cinemalumiere.fr',
            'description' => 'Un cinéma moderne proposant les derniers films à l\'affiche.'
        ],
    ];
    
    public function getAllPartners() {
        return $this->partners;
    }
    
    public function getPartnerById($id) {
        return array_filter($this->partners, function($partner) use ($id) {
            return $partner['id'] == $id;
        })[0] ?? null;
    }
}