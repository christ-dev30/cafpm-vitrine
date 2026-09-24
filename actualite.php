<?php
/* ==========================================================================
   ARTICLE D'ACTUALITÉ : actualite.php?a=<slug>
   --------------------------------------------------------------------------
   Le texte complet de l'article est la clé 'corps' du tableau $actualites
   (config/contenu.php). Slug inconnu -> page 404.
   ========================================================================== */

require_once __DIR__ . '/partials/amorce.php';

$slug        = parametre_get('a'); // Texte uniquement (un tableau ?a[]= est ignoré)
$article     = trouver_par_slug($actualites, $slug);

// Article inconnu : on affiche la page 404 (avec le bon code HTTP)
if ($article === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Solution liée à l'article (encadré en fin d'article)
$solution_liee = trouver_par_slug($solutions, $article['solution'] ?? '');

$titre_page       = $article['titre'];
$description_page = $article['texte'];
$page_active      = 'actualites';

$bandeau = [
    'fil'   => [['Accueil', 'index.php'], ['Actualités', 'actualites.php'], [$article['categorie'], null]],
    'meta'  => $article['date'] . ' • ' . $article['categorie'],
    'titre' => $article['titre'],
    'texte' => $article['texte'],
];

require __DIR__ . '/partials/header.php';
?>
    <main id="contenu">
        <?php require __DIR__ . '/partials/bandeau-page.php'; ?>

        <section class="page-section">
            <div class="container grille-article">
                <article class="prose article-corps">
                    <p class="article-date">Publié le <time datetime="<?= e($article['date_iso']) ?>"><?= e($article['date']) ?></time></p>
                    <?php afficher_blocs($article['corps']); ?>

                    <div class="article-actions">
                        <?php if ($article['categorie'] === 'Services'): ?>
                        <button type="button" class="btn btn-primary modal-trigger" data-target="drawer-form">Demander un devis &rarr;</button>
                        <?php else: ?>
                        <button type="button" class="btn btn-primary modal-trigger" data-target="drawer-candidat">Créer mon profil candidat &rarr;</button>
                        <button type="button" class="btn btn-secondary modal-trigger" data-target="drawer-form">Déposer un besoin</button>
                        <?php endif; ?>
                    </div>

                    <p class="article-retour"><a href="<?= e(lien_site('actualites.php')) ?>">&larr; Toutes les actualités</a></p>
                </article>

                <aside class="article-aside">
                    <?php if ($solution_liee !== null): ?>
                    <div class="carte carte-accent">
                        <span class="section-eyebrow">Solution associée</span>
                        <h2><?= e($solution_liee['titre']) ?></h2>
                        <p><?= e($solution_liee['texte']) ?></p>
                        <a href="<?= e(lien_site('solution.php?s=' . $solution_liee['slug'])) ?>" class="link-arrow">Découvrir cette solution &rarr;</a>
                    </div>
                    <?php endif; ?>

                    <div class="carte">
                        <h2>Autres actualités</h2>
                        <ul class="liste-liens">
                            <?php foreach ($actualites as $autre): ?>
                                <?php if ($autre['slug'] === $article['slug']) continue; ?>
                            <li>
                                <a href="<?= e(lien_site('actualite.php?a=' . $autre['slug'])) ?>"><?= e($autre['titre']) ?></a>
                                <span class="liste-liens-meta"><?= e($autre['date']) ?> • <?= e($autre['categorie']) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/partials/fin-page.php'; ?>
