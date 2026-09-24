<?php
/* ==========================================================================
   PAGE 404 : PAGE INTROUVABLE
   --------------------------------------------------------------------------
   S'utilise seule (adresse /404.php, ou "ErrorDocument 404 /404.php" dans
   .htaccess) OU incluse depuis un autre script quand un contenu n'existe
   pas, par exemple dans solution.php :
       require __DIR__ . '/404.php';
       exit;
   ATTENTION : l'inclure AVANT tout affichage (le header n'a pas encore été
   envoyé), sinon le code HTTP 404 ne peut plus être transmis.
   ========================================================================== */

// Appelée pour une adresse inconnue (ex. /dossier/inexistant), la page ne peut
// pas deviner sa position : ses liens partent alors de la racine du site ("/").
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') !== basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '')
    && !defined('PREFIXE_RACINE')) {
    define('PREFIXE_RACINE', '/');
}

require_once __DIR__ . '/partials/amorce.php';

if (!headers_sent()) {
    http_response_code(404);
}

$titre_page       = 'Page introuvable';
$description_page = "La page demandée n'existe pas ou a été déplacée.";
$page_active      = '404';

require __DIR__ . '/partials/header.php';
?>
    <main id="contenu" class="page-erreur">
        <div class="container">
            <div class="erreur-carte">
                <span class="erreur-code" aria-hidden="true">404</span>
                <h1>Oups, cette page est introuvable</h1>
                <p>La page que vous cherchez n'existe pas, a été déplacée ou l'adresse contient une erreur. Pas d'inquiétude : voici quelques liens pour retrouver votre chemin.</p>

                <div class="erreur-actions">
                    <a href="<?= e(lien_site('index.php')) ?>" class="btn btn-primary">Retour à l'accueil</a>
                    <a href="<?= e(lien_site('contact.php')) ?>" class="btn btn-secondary">Nous contacter</a>
                </div>

                <h2>Nos solutions</h2>
                <ul class="liste-liens">
                    <?php foreach ($solutions as $sol): ?>
                    <li><a href="<?= e(lien_site('solution.php?s=' . $sol['slug'])) ?>"><?= e($sol['titre']) ?> &rarr;</a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= e(lien_site('actualites.php')) ?>">Nos actualités &rarr;</a></li>
                </ul>
            </div>
        </div>
    </main>
<?php require __DIR__ . '/partials/fin-page.php'; ?>
