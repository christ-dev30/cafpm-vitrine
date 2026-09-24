<?php /* ==========================================================================
   SECTION HERO : titre principal, boutons d'appel à l'action, chiffres clés
   Les chiffres viennent de $statistiques (config/contenu.php)
   ========================================================================== */ ?>
    <section class="hero" id="hero">
        <div class="hero-bg reveal"></div>
        <div class="hero-badge reveal delai-6" aria-hidden="true">Votre partenaire<br>en solutions RH</div>

        <div class="container hero-inner">
            <div class="hero-content">
                <div class="hero-tags reveal">
                    <span>Intérim</span> • <span>Placement</span> • <span>Formation</span> • <span>Mise à disposition</span>
                </div>
                <h1 class="reveal delai-1">Les bonnes compétences,<br><span>au bon moment.</span></h1>
                <p class="text-max-width reveal delai-2">CAFPM vous accompagne dans le recrutement, la mise à disposition, la gestion et la formation de votre personnel, temporaire ou permanent.</p>

                <div class="hero-cta-group reveal delai-3">
                    <button type="button" class="hero-cta-card primary modal-trigger" data-target="drawer-form">
                        <div class="hero-cta-icon"><?= icone('calendrier') ?></div>
                        <div class="hero-cta-text">
                            <span>Je suis une entreprise</span>
                            <strong>Trouver du personnel</strong>
                        </div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>

                    <button type="button" class="hero-cta-card secondary modal-trigger" data-target="drawer-candidat">
                        <div class="hero-cta-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="hero-cta-text">
                            <span>Je suis un candidat</span>
                            <strong>Trouver un emploi</strong>
                        </div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

                <!-- Chiffres clés (boucle sur $statistiques) -->
                <div class="hero-stats reveal delai-4">
                    <?php foreach ($statistiques as $stat): ?>
                    <div class="stat-item">
                        <div class="stat-icon"><?= icone($stat['icone'], 24, 'var(--color-gold)') ?></div>
                        <div>
                            <span class="stat-val"><?= e($stat['valeur']) ?></span>
                            <span class="stat-lbl"><?= e($stat['libelle']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
