<?php /* ==========================================================================
   SECTION SECTEURS D'INTERVENTION (boucle sur $secteurs)
   Chaque carte mène à la page solution la plus pertinente (clé 'lien').
   ========================================================================== */ ?>
    <section class="secteurs" id="secteurs">
        <div class="container">
            <div class="reveal">
                <span class="section-eyebrow" aria-hidden="true">Nos secteurs d'intervention</span>
                <h2 class="sr-only">Nos secteurs d'intervention</h2>
                <p class="secteurs-intro">Une expertise ciblée dans les secteurs où nous apportons une réelle valeur ajoutée. Votre activité n'y figure pas&nbsp;? <a href="<?= e(lien_site('contact.php')) ?>">Parlons-en</a>.</p>
            </div>

            <div class="secteurs-grid">
                <?php foreach ($secteurs as $i => $secteur): ?>
                <a href="<?= e(lien_site($secteur['lien'])) ?>" class="secteur-card secteur-card-lien reveal<?= $i ? ' delai-' . min($i, 6) : '' ?>">
                    <div class="secteur-head">
                        <?= icone($secteur['icone'], 32, 'currentColor', 1.5) ?>
                        <h3><?= e($secteur['titre']) ?></h3>
                    </div>
                    <p><?= e($secteur['texte']) ?></p>
                    <span class="link-arrow"><?= e($secteur['libelle_lien']) ?> &rarr;</span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
