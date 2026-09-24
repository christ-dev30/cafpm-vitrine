<?php
/* ==========================================================================
   CRÉER (OU MODIFIER) UN COMPTE ADMINISTRATEUR - EN LIGNE DE COMMANDE
   --------------------------------------------------------------------------
   Utilisation (depuis le dossier du site, dans un terminal) :
       php admin/creer-admin.php email@cafpm.ci "MotDePasse" "Nom Prénom"
   - Si l'email n'existe pas : le compte est créé.
   - S'il existe déjà : son mot de passe et son nom sont remplacés
     (pratique en cas de mot de passe oublié).
   Sécurité : ce script refuse de s'exécuter depuis un navigateur (erreur 404),
   et il est aussi bloqué par .htaccess (Apache) et router.php (serveur local).
   ========================================================================== */

// 1. Uniquement en ligne de commande
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';

// 2. Lecture des arguments
[, $email, $mot_de_passe, $nom] = array_pad($argv, 4, '');
$email = trim($email);
$nom   = trim($nom);

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $mot_de_passe === '' || $nom === '') {
    fwrite(STDERR, "Utilisation : php admin/creer-admin.php email motdepasse \"Nom\"\n");
    exit(1);
}
if ($erreur = erreur_mot_de_passe($mot_de_passe, $mot_de_passe)) {
    fwrite(STDERR, "Erreur : $erreur\n");
    exit(1);
}

// 3. Création ou mise à jour du compte (mot de passe haché, jamais en clair)
try {
    $requete = db()->prepare(
        'INSERT INTO admins (nom, email, mot_de_passe) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE nom = VALUES(nom), mot_de_passe = VALUES(mot_de_passe)'
    );
    $requete->execute([$nom, $email, password_hash($mot_de_passe, PASSWORD_DEFAULT)]);
} catch (PDOException $erreur) {
    fwrite(STDERR, 'Erreur base de données : ' . $erreur->getMessage() . "\n");
    exit(1);
}

// rowCount : 1 = compte créé, 2 = compte existant mis à jour
echo ($requete->rowCount() === 1 ? 'Compte administrateur créé' : 'Compte administrateur mis à jour')
    . " : $email\nConnexion : " . SITE_URL . "/admin/connexion.php\n";
