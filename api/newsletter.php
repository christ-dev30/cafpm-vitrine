<?php
/* ==========================================================================
   TRAITEMENT : inscription à la newsletter (pied de page)
   --------------------------------------------------------------------------
   Enregistre l'email dans la table `newsletter`.
   Un même email ne peut être inscrit qu'une seule fois (INSERT IGNORE +
   colonne UNIQUE dans la base).
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';

exiger_post();
verifier_csrf();

$email = champ('email', 190);

// Vérifie que l'email a un format valide
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    repondre_json(false, 'Adresse email invalide.', 422);
}

try {
    $requete = db()->prepare('INSERT IGNORE INTO newsletter (email) VALUES (?)');
    $requete->execute([$email]);
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur newsletter : ' . $erreur->getMessage());
    repondre_json(false, 'Une erreur est survenue. Veuillez réessayer plus tard.', 500);
}

repondre_json(true, 'Merci ! Vous êtes inscrit à notre newsletter.');
