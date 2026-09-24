<?php
/* ==========================================================================
   PAGE DE DÉTAIL D'UNE SOLUTION : solution.php?s=<slug>
   --------------------------------------------------------------------------
   Le contenu (introduction, pour qui, étapes, avantages, FAQ...) vient du
   tableau $solutions de config/contenu.php. Pour modifier un texte, c'est
   là-bas qu'il faut aller. Slug inconnu -> page 404.
   ========================================================================== */

require_once __DIR__ . '/partials/amorce.php';

$slug     = parametre_get('s'); // Texte uniquement (un tableau ?s[]= est ignoré)
$solution = trouver_par_slug($solutions, $slug);

// Solution inconnue : on affiche la page 404 (avec le bon code HTTP)
if ($solution === null) {
    require __DIR__ . '/404.php';
    exit;
}

$titre_page       = $solution['titre'];
$description_page = $solution['titre'] . ' avec CAFPM à Abidjan : ' . $solution['accroche'];
$page_active      = 'solutions';

// Boutons d'appel à l'action (la formation s'adresse aussi aux candidats)
$boutons = [['libelle' => 'Déposer un besoin', 'cible' => 'drawer-form', 'style' => 'primary']];
if (!empty($solution['candidat'])) {
    $boutons[] = ['libelle' => 'Je suis candidat', 'cible' => 'drawer-candidat', 'style' => 'secondary'];
} else {
    $boutons[] = ['libelle' => 'Nous contacter', 'lien' => 'contact.php?sujet=Demande+de+personnel', 'style' => 'secondary'];
}

$bandeau = [
    'fil'      => [['Accueil', 'index.php'], ['Nos solutions', 'index.php#solutions'], [$solution['titre'], null]],
    'surtitre' => 'Nos solutions',
    'titre'    => $solution['titre'],
    'texte'    => $solution['accroche'],
    'boutons'  => $boutons,
];

require __DIR__ . '/partials/header.php';
?>
    <main id="contenu">
        <?php require __DIR__ . '/partials/bandeau-page.php'; ?>

        <!-- Introduction + Pour qui ? -->
        <section class="page-section">
            <div class="container grille-intro">
                <div class="prose">
                    <h2>En quelques mots</h2>
                    <?php foreach ($solution['intro'] as $paragraphe): ?>
                    <p><?= e($paragraphe) ?></p>
                    <?php endforeach; ?>
                </div>
                <aside class="carte carte-accent">
                    <h2>Pour qui ?</h2>
                    <ul class="liste-coches">
                        <?php foreach ($solution['pour_qui'] as $cible): ?>
                        <li><?= icone('coche', 18) ?><span><?= e($cible) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </div>
        </section>

        <!-- Ce que CAFPM fait concrètement -->
        <section class="page-section page-section-blanche">
            <div class="container">
                <div class="section-head">
                    <span class="section-eyebrow">Notre accompagnement</span>
                    <h2>Ce que CAFPM fait pour vous</h2>
                </div>
                <ul class="grille-missions">
                    <?php foreach ($solution['missions'] as $mission): ?>
                    <li><?= icone('coche', 20) ?><span><?= e($mission) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <!-- Déroulement en étapes -->
        <section class="page-section">
            <div class="container">
                <div class="section-head">
                    <span class="section-eyebrow">Déroulement</span>
                    <h2>Comment ça se passe ?</h2>
                </div>
                <ol class="etapes">
                    <?php foreach ($solution['etapes'] as [$titre_etape, $texte_etape]): ?>
                    <li class="etape">
                        <h3><?= e($titre_etape) ?></h3>
                        <p><?= e($texte_etape) ?></p>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>

        <!-- Avantages -->
        <section class="page-section page-section-blanche">
            <div class="container">
                <div class="section-head">
                    <span class="section-eyebrow">Pourquoi CAFPM ?</span>
                    <h2>Les avantages pour vous</h2>
                </div>
                <div class="grille-cartes">
                    <?php foreach ($solution['avantages'] as [$titre_avantage, $texte_avantage]): ?>
                    <div class="carte">
                        <h3><?= e($titre_avantage) ?></h3>
                        <p><?= e($texte_avantage) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Questions fréquentes -->
        <section class="page-section">
            <div class="container conteneur-etroit">
                <div class="section-head">
                    <span class="section-eyebrow">FAQ</span>
                    <h2>Questions fréquentes</h2>
                </div>
                <div class="faq">
                    <?php foreach ($solution['faq'] as [$question, $reponse]): ?>
                    <details>
                        <summary><?= e($question) ?></summary>
                        <p><?= e($reponse) ?></p>
                    </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Appel à l'action -->
        <section class="bandeau-cta">
            <div class="container bandeau-cta-inner">
                <div>
                    <h2><?= e($solution['cta_titre']) ?></h2>
                    <p><?= e($solution['cta_texte']) ?></p>
                </div>
                <div class="bandeau-cta-actions">
                    <button type="button" class="btn btn-primary modal-trigger" data-target="drawer-form">Déposer un besoin &rarr;</button>
                    <?php if (!empty($solution['candidat'])): ?>
                    <button type="button" class="btn btn-secondary modal-trigger" data-target="drawer-candidat">Créer mon profil candidat</button>
                    <?php else: ?>
                    <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_TELEPHONE)) ?>" class="btn btn-secondary">Appeler le <?= e(SITE_TELEPHONE) ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Autres solutions -->
        <section class="page-section">
            <div class="container">
                <div class="section-head">
                    <span class="section-eyebrow">Aller plus loin</span>
                    <h2>Nos autres solutions</h2>
                </div>
                <div class="grille-autres">
                    <?php foreach ($solutions as $autre): ?>
                        <?php if ($autre['slug'] === $solution['slug']) continue; ?>
                    <a href="<?= e(lien_site('solution.php?s=' . $autre['slug'])) ?>" class="carte carte-lien">
                        <span class="carte-icone"><?= icone($autre['icone'], 24) ?></span>
                        <h3><?= e($autre['titre']) ?></h3>
                        <p><?= e($autre['texte']) ?></p>
                        <span class="link-arrow">En savoir plus &rarr;</span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/partials/fin-page.php'; ?>
