<?php /* ==========================================================================
   SECTION TÉMOIGNAGES (défilement infini)
   La liste $temoignages est affichée DEUX fois : c'est ce qui permet
   à l'animation CSS de boucler sans coupure visible.
   ========================================================================== */ ?>
    <section class="testimonials-section reveal" id="references">
        <div class="container">
            <div class="section-head section-head-centre reveal">
                <span class="section-eyebrow">Témoignages</span>
                <h2>Ce que nos partenaires disent de nous</h2>
            </div>

            <div class="testimonials-marquee-container">
                <div class="testimonials-marquee-track">
                    <?php for ($passage = 0; $passage < 2; $passage++): ?>
                        <?php foreach ($temoignages as $temoignage): ?>
                    <div class="testimonial-card"<?= $passage ? ' aria-hidden="true"' : '' ?>>
                        <svg class="quote-icon" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <blockquote>
                            "<?= e($temoignage['texte']) ?>"
                        </blockquote>
                        <div class="testimonial-author">
                            <?php if ($temoignage['avatar']): ?>
                            <div class="ta-avatar" aria-hidden="true" style="background-image: url('<?= e($temoignage['avatar']) ?>');"></div>
                            <?php else: ?>
                            <div class="ta-avatar ta-avatar-vide" aria-hidden="true"></div>
                            <?php endif; ?>
                            <div class="ta-info">
                                <strong><?= e($temoignage['nom']) ?></strong>
                                <span><?= e($temoignage['poste']) ?></span>
                            </div>
                        </div>
                    </div>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>
