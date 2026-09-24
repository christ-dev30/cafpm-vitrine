<?php
/* ==========================================================================
   PAGE D'ACCUEIL CAFPM (point d'entrée du site)
   --------------------------------------------------------------------------
   Cette page ne contient pas de HTML directement : elle assemble les
   différentes sections qui se trouvent dans le dossier partials/.
   Pour modifier une section, ouvrez le fichier correspondant ci-dessous.
   ========================================================================== */

require_once __DIR__ . '/partials/amorce.php';   // Réglages, textes, fonctions
require_once __DIR__ . '/includes/db.php';        // Connexion MySQL

// Connexion à la base dès l'ouverture du site : les tables sont créées
// automatiquement si elles n'existent pas. Si MySQL est éteint, la page
// s'affiche quand même (seuls les formulaires ne fonctionneront pas).
try {
    db();
} catch (PDOException $erreur) {
    error_log('[CAFPM] Base de données indisponible : ' . $erreur->getMessage());
}

// Informations de la page (voir partials/header.php)
$page_active = 'accueil';   // Titre et description par défaut (SITE_TITRE, SITE_DESC)

require __DIR__ . '/partials/header.php';      // <head>, logo, menu
?>
    <main id="contenu">
<?php
require __DIR__ . '/partials/hero.php';        // Bannière principale + chiffres clés
require __DIR__ . '/partials/apropos.php';     // À propos
require __DIR__ . '/partials/solutions.php';   // Solutions, recherche, entreprise/candidat
require __DIR__ . '/partials/secteurs.php';    // Secteurs d'intervention
require __DIR__ . '/partials/actualites.php';  // Actualités
require __DIR__ . '/partials/temoignages.php'; // Témoignages
?>
    </main>
<?php
require __DIR__ . '/partials/fin-page.php';    // Pied de page, fenêtres, main.js
