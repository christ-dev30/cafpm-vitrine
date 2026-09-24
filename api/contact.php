<?php
/* ==========================================================================
   TRAITEMENT : formulaire de la page Contact
   --------------------------------------------------------------------------
   Champs attendus : nom, email, telephone (optionnel), sujet, message
   1. Vérifie la sécurité (POST + jeton CSRF)
   2. Récupère et contrôle les champs
   3. Enregistre le message dans la table `messages` (lisible dans admin/messages.php)
   4. Prévient CAFPM par email ("Répondre" répond directement au visiteur)
   5. Répond au JavaScript en JSON
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';

// 1. Sécurité
exiger_post();
verifier_csrf();

// 2. Récupération des champs du formulaire
$nom       = champ('nom', 150);
$email     = champ('email', 190);
$telephone = champ('telephone', 30);
$sujet     = champ('sujet', 150);
$message   = champ('message', 5000);

if ($nom === '' || $email === '' || $sujet === '' || $message === '') {
    repondre_json(false, 'Merci de remplir tous les champs obligatoires.', 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    repondre_json(false, 'Adresse email invalide.', 422);
}

// 3. Enregistrement en base de données (téléphone vide = NULL)
try {
    $requete = db()->prepare(
        'INSERT INTO messages (nom, email, telephone, sujet, message) VALUES (?, ?, ?, ?, ?)'
    );
    $requete->execute([$nom, $email, $telephone !== '' ? $telephone : null, $sujet, $message]);
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur contact : ' . $erreur->getMessage());
    repondre_json(false, 'Une erreur est survenue. Veuillez réessayer plus tard.', 500);
}

// 4. Notification email à CAFPM
envoyer_email(
    'Nouveau message : ' . $sujet,
    "Nom : $nom\nEmail : $email\nTéléphone : " . ($telephone !== '' ? $telephone : '-')
    . "\nSujet : $sujet\n\nMessage :\n$message",
    null,
    $email
);

// 5. Réponse
repondre_json(true, 'Message envoyé ! Nous vous répondrons rapidement.');
