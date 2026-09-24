<?php
/* ==========================================================================
   BANDEAU DE TITRE DES PAGES INTÉRIEURES (fil d'Ariane + titre + intro)
   --------------------------------------------------------------------------
   Définir $bandeau avant d'inclure ce fichier :
     $bandeau = [
         'fil'     => [['Accueil', 'index.php'], ['Contact', null]], // null = page actuelle
         'surtitre'=> 'Nous contacter',          // facultatif
         'titre'   => 'Contact',
         'texte'   => 'Texte d’introduction',    // facultatif
         'meta'    => '12 Septembre 2025 • Recrutement', // facultatif (articles)
     ];
   Les boutons éventuels sont ajoutés via $bandeau['boutons'] :
     [['libelle' => '...', 'cible' => 'drawer-form', 'style' => 'primary'], ...]
     (cible = fenêtre à ouvrir) ou ['libelle' => '...', 'lien' => 'contact.php']
   ========================================================================== */
?>
    <section class="page-hero">
        <div class="container">
            <?php if (!empty($bandeau['fil'])): ?>
            <nav class="fil-ariane" aria-label="Fil d'Ariane">
                <ol>
                    <?php foreach ($bandeau['fil'] as [$libelle, $lien]): ?>
                    <li><?php if ($lien !== null): ?><a href="<?= e(lien_site($lien)) ?>"><?= e($libelle) ?></a><?php else: ?><span aria-current="page"><?= e($libelle) ?></span><?php endif; ?></li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>

            <?php if (!empty($bandeau['surtitre'])): ?>
            <span class="section-eyebrow"><?= e($bandeau['surtitre']) ?></span>
            <?php endif; ?>
            <?php if (!empty($bandeau['meta'])): ?>
            <div class="page-hero-meta"><?= e($bandeau['meta']) ?></div>
            <?php endif; ?>

            <h1><?= e($bandeau['titre']) ?></h1>

            <?php if (!empty($bandeau['texte'])): ?>
            <p class="page-hero-texte"><?= e($bandeau['texte']) ?></p>
            <?php endif; ?>

            <?php if (!empty($bandeau['boutons'])): ?>
            <div class="page-hero-actions">
                <?php foreach ($bandeau['boutons'] as $bouton): ?>
                    <?php $classe = 'btn btn-' . ($bouton['style'] ?? 'primary'); ?>
                    <?php if (!empty($bouton['cible'])): ?>
                <button type="button" class="<?= e($classe) ?> modal-trigger" data-target="<?= e($bouton['cible']) ?>"><?= e($bouton['libelle']) ?></button>
                    <?php else: ?>
                <a href="<?= e(lien_site($bouton['lien'])) ?>" class="<?= e($classe) ?>"><?= e($bouton['libelle']) ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
