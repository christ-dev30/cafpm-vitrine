<?php /* ==========================================================================
   SECTION ACTUALITÉS & OPPORTUNITÉS (boucle sur $actualites)
   "Lire la suite" mène à l'article complet : actualite.php?a=<slug>
   ========================================================================== */ ?>
    <section class="news-section" id="actualites">
        <div class="container">
            <div class="section-head reveal">
                <span class="section-eyebrow">En direct de CAFPM</span>
                <h2>Actualités & opportunités</h2>
            </div>

            <div class="news-grid">
                <?php foreach ($actualites as $i => $actu): ?>
                <article class="news-card reveal<?= $i ? ' delai-' . min($i, 6) : '' ?>">
                    <div class="news-meta"><time datetime="<?= e($actu['date_iso']) ?>"><?= e($actu['date']) ?></time> • <?= e($actu['categorie']) ?></div>
                    <h3><a href="<?= e(lien_site('actualite.php?a=' . $actu['slug'])) ?>"><?= e($actu['titre']) ?></a></h3>
                    <p><?= e($actu['texte']) ?></p>
                    <a href="<?= e(lien_site('actualite.php?a=' . $actu['slug'])) ?>" class="news-link" aria-label="Lire la suite : <?= e($actu['titre']) ?>">Lire la suite &rarr;</a>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="section-cta reveal">
                <a href="<?= e(lien_site('actualites.php')) ?>" class="btn btn-secondary">Toutes les actualités &rarr;</a>
            </div>
        </div>
    </section>
