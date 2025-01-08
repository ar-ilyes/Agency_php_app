const mysql = require('mysql');

// Configuration de la connexion à la base de données
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Remplacez par votre utilisateur MySQL
  password: 'root', // Remplacez par votre mot de passe MySQL
  database: 'association_db'
});

// Données de test
const seedData = async () => {
  const utilisateurs = [
    ['Admin', 'System', 'admin@association.com', 'admin123', 'admin'],
    ['Dubois', 'Jean', 'jean.dubois@email.com', 'member123', 'membre'],
    ['Martin', 'Sophie', 'sophie.martin@email.com', 'member456', 'membre'],
    ['Hotel Luxe', 'Manager', 'contact@hotelluxe.com', 'partner123', 'partenaire'],
    ['Clinique Santé', 'Manager', 'contact@clinique.com', 'partner456', 'partenaire']
  ];

  const membres = [
    [2, 'classique', 'QR12345', '2025-12-31', 100],
    [3, 'premium', 'QR67890', '2025-12-31', 500]
  ];

  const partenaires = [
    [4, 'Hotel Luxe', 'hotel', 'Paris', 'Hotel 5 étoiles au cœur de Paris'],
    [5, 'Clinique Santé', 'clinique', 'Lyon', 'Clinique privée spécialisée']
  ];

  const remises = [
    [1, 20.00, '2024-01-01', '2024-06-30', false, 'Valable sur tous les séjours'],
    [2, 15.00, '2024-01-01', '2024-04-01', true, 'Valable sur les consultations']
  ];

  try {
    // Insérer les utilisateurs
    for (const user of utilisateurs) {
      const query = `
        INSERT INTO UTILISATEUR (nom, prenom, email, mot_de_passe, role)
        VALUES (?, ?, ?, SHA2(?, 256), ?)
      `;
      await new Promise((resolve, reject) => {
        connection.query(query, user, (err) => (err ? reject(err) : resolve()));
      });
    }

    // Insérer les membres
    for (const member of membres) {
      const query = `
        INSERT INTO MEMBRE (id_utilisateur, type_carte, qr_code, date_expiration, points_fidelite)
        VALUES (?, ?, ?, ?, ?)
      `;
      await new Promise((resolve, reject) => {
        connection.query(query, member, (err) => (err ? reject(err) : resolve()));
      });
    }

    // Insérer les partenaires
    for (const partner of partenaires) {
      const query = `
        INSERT INTO PARTENAIRE (id_utilisateur, nom_etablissement, categorie, ville, description)
        VALUES (?, ?, ?, ?, ?)
      `;
      await new Promise((resolve, reject) => {
        connection.query(query, partner, (err) => (err ? reject(err) : resolve()));
      });
    }

    // Insérer les remises
    for (const remise of remises) {
      const query = `
        INSERT INTO REMISE (id_partenaire, pourcentage, date_debut, date_fin, offre_speciale, conditions)
        VALUES (?, ?, ?, ?, ?, ?)
      `;
      await new Promise((resolve, reject) => {
        connection.query(query, remise, (err) => (err ? reject(err) : resolve()));
      });
    }

    console.log('Base de données peuplée avec succès !');
  } catch (err) {
    console.error('Erreur lors de l’insertion des données :', err);
  } finally {
    connection.end();
  }
};

// Connecter et exécuter
connection.connect((err) => {
  if (err) {
    console.error('Erreur de connexion à la base de données :', err);
    return;
  }
  console.log('Connexion réussie à la base de données.');
  seedData();
});
