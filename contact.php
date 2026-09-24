<?php
/* ==========================================================================
   PAGE CONTACT : coordonnées, horaires, plan d'accès et formulaire
   --------------------------------------------------------------------------
   Le formulaire est envoyé par main.js (fetch) à api/contact.php.
   Champs attendus par api/contact.php (ne pas renommer) :
     nom (requis), email (requis), telephone (facultatif),
     sujet (requis, valeurs de $sujets_contact), message (requis)
   Un sujet peut être présélectionné par l'adresse : contact.php?sujet=Formation
   ========================================================================== */

require_once __DIR__ . '/partials/amorce.php';

// Sujet présélectionné (uniquement s'il fait partie de la liste autorisée)
$sujet_choisi = parametre_get('sujet'); // Texte uniquement (un tableau ?sujet[]= est ignoré)
if (!in_array($sujet_choisi, $sujets_contact, true)) {
    $sujet_choisi = '';
}

$telephone_lien = preg_replace('/[^0-9+]/', '', SITE_TELEPHONE);

$titre_page       = 'Contact';
$description_page = "Contactez CAFPM à Abidjan (Cocody Riviera Bonoumin) : demande de personnel, candidature, formation ou partenariat.";
$page_active      = 'contact';

$bandeau = [
    'fil'      => [['Accueil', 'index.php'], ['Contact', null]],
    'surtitre' => 'Parlons de votre projet',
    'titre'    => 'Contactez CAFPM',
    'texte'    => "Une question, un besoin en personnel, une candidature ou un projet de formation ? Notre équipe vous répond avec plaisir.",
];

require __DIR__ . '/partials/header.php';
?>
    <main id="contenu">
        <?php require __DIR__ . '/partials/bandeau-page.php'; ?>

        <section class="page-section">
            <div class="container grille-contact">

                <!-- Coordonnées -->
                <div class="contact-infos">
                    <div class="carte contact-carte">
                        <span class="contact-icone"><?= icone('lieu', 22) ?></span>
                        <div>
                            <h2>Adresse</h2>
                            <p><?= e(SITE_NOM . ' ' . SITE_FORME) ?><br><?= e(SITE_ADRESSE) ?><br>Côte d'Ivoire</p>
                            <a href="<?= e(LIEN_GOOGLE_MAPS) ?>" class="link-arrow" target="_blank" rel="noopener">Voir sur Google Maps &rarr;</a>
                        </div>
                    </div>

                    <div class="carte contact-carte">
                        <span class="contact-icone"><?= icone('telephone', 22) ?></span>
                        <div>
                            <h2>Téléphone</h2>
                            <p><a href="tel:<?= e($telephone_lien) ?>" class="contact-lien"><?= e(SITE_TELEPHONE) ?></a></p>
                        </div>
                    </div>

                    <div class="carte contact-carte">
                        <span class="contact-icone"><?= icone('email', 22) ?></span>
                        <div>
                            <h2>Email</h2>
                            <p><a href="mailto:<?= e(EMAIL_RECEPTION) ?>" class="contact-lien"><?= e(EMAIL_RECEPTION) ?></a></p>
                        </div>
                    </div>

                    <div class="carte contact-carte">
                        <span class="contact-icone"><?= icone('horloge', 22) ?></span>
                        <div>
                            <h2>Horaires d'ouverture</h2>
                            <ul class="liste-horaires">
                                <?php foreach ($horaires as $horaire): ?>
                                <li><span><?= e($horaire['jours']) ?></span><strong><?= e($horaire['heures']) ?></strong></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if ($horaires_a_confirmer): ?>
                            <p class="a-completer">[À confirmer : horaires d'ouverture réels]</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="carte carte-accent">
                        <h2>Vous préférez aller plus vite&nbsp;?</h2>
                        <p>Entreprises et candidats peuvent aussi utiliser nos formulaires dédiés.</p>
                        <div class="boutons-ligne">
                            <button type="button" class="btn btn-primary modal-trigger" data-target="drawer-form">Déposer un besoin</button>
                            <button type="button" class="btn btn-secondary modal-trigger" data-target="drawer-candidat">Créer mon profil</button>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact -> api/contact.php -->
                <div class="carte contact-formulaire">
                    <h2>Écrivez-nous</h2>
                    <p>Les champs marqués d'un astérisque (<span aria-hidden="true">*</span>) sont obligatoires. Nous vous répondons dans les meilleurs délais.</p>

                    <form class="interactive-form" action="<?= e(lien_site('api/contact.php')) ?>" method="post">
                        <div class="input-group">
                            <label for="contact-nom">Nom complet *</label>
                            <input type="text" id="contact-nom" name="nom" autocomplete="name" required>
                        </div>
                        <div class="grille-champs">
                            <div class="input-group">
                                <label for="contact-email">Adresse email *</label>
                                <input type="email" id="contact-email" name="email" autocomplete="email" required>
                            </div>
                            <div class="input-group">
                                <label for="contact-telephone">Téléphone (facultatif)</label>
                                <input type="tel" id="contact-telephone" name="telephone" autocomplete="tel">
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="contact-sujet">Sujet *</label>
                            <select id="contact-sujet" name="sujet" required>
                                <option value="">Sélectionner un sujet...</option>
                                <?php foreach ($sujets_contact as $sujet): ?>
                                <option value="<?= e($sujet) ?>"<?= $sujet === $sujet_choisi ? ' selected' : '' ?>><?= e($sujet) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="contact-message">Message *</label>
                            <textarea id="contact-message" name="message" rows="6" required placeholder="Expliquez-nous votre demande..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-bloc">Envoyer le message &rarr;</button>
                        <p class="form-mention">Vos informations sont utilisées uniquement pour répondre à votre message. <a href="<?= e(lien_site('confidentialite.php')) ?>">En savoir plus sur la protection de vos données</a>.</p>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/partials/fin-page.php'; ?>
