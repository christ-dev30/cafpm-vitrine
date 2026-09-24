<?php
/* ==========================================================================
   FENÊTRES INTERACTIVES : connexion, formulaires entreprise et candidat,
   notifications (toasts). Chaque formulaire indique dans "action" le
   fichier PHP qui le traite (dossier api/). Incluses sur TOUTES les pages
   publiques, juste avant <script src="main.js">.
   ========================================================================== */
$lien_confidentialite = lien_site('confidentialite.php');
?>

    <!-- Overlay (fond assombri derrière les fenêtres) -->
    <div class="overlay" id="overlay" aria-hidden="true"></div>

    <!-- Modal Connexion Espace Client -> api/connexion.php -->
    <div class="modal" id="modal-login" role="dialog" aria-modal="true" aria-labelledby="modal-login-titre" tabindex="-1">
        <button type="button" class="modal-close" aria-label="Fermer la fenêtre">&times;</button>
        <div class="modal-header">
            <h3 id="modal-login-titre">Connexion à l'espace client</h3>
            <p>Accédez à votre tableau de bord sécurisé pour suivre vos demandes.</p>
        </div>
        <form class="interactive-form" action="<?= e(lien_site('api/connexion.php')) ?>" method="post">
            <div class="input-group">
                <label for="login-email">Adresse email</label>
                <input type="email" id="login-email" name="email" required autocomplete="email" placeholder="contact@entreprise.com">
            </div>
            <div class="input-group">
                <label for="login-mdp">Mot de passe</label>
                <input type="password" id="login-mdp" name="mot_de_passe" required autocomplete="current-password" placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary btn-bloc">Se connecter</button>
            <p class="modal-lien-secondaire"><a href="<?= e(lien_site('espace-client/mot-de-passe-oublie.php')) ?>">Mot de passe oublié ?</a></p>
        </form>
    </div>

    <!-- Tiroir "Déposer un besoin" (entreprise) -> api/demande.php -->
    <div class="drawer" id="drawer-form" role="dialog" aria-modal="true" aria-labelledby="drawer-form-titre" tabindex="-1">
        <button type="button" class="modal-close" aria-label="Fermer la fenêtre">&times;</button>
        <div class="drawer-header">
            <h3 id="drawer-form-titre">Déposer un besoin</h3>
            <p>Décrivez votre besoin en personnel ou en formation : un conseiller CAFPM vous recontacte sous 24 h.</p>
        </div>
        <div class="drawer-content">
            <form class="interactive-form" action="<?= e(lien_site('api/demande.php')) ?>" method="post">
                <div class="input-group">
                    <label for="demande-entreprise">Nom de l'entreprise</label>
                    <input type="text" id="demande-entreprise" name="entreprise" autocomplete="organization" required>
                </div>
                <div class="input-group">
                    <label for="demande-contact">Email ou téléphone de contact</label>
                    <input type="text" id="demande-contact" name="contact" required>
                </div>
                <div class="input-group">
                    <label for="demande-secteur">Secteur d'activité</label>
                    <select id="demande-secteur" name="secteur" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($domaines as $domaine): ?>
                        <option><?= e($domaine) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="demande-profil">Profil recherché</label>
                    <input type="text" id="demande-profil" name="profil" placeholder="Ex. : Secrétaire, Vendeur, Agent d'entretien" required>
                </div>
                <div class="input-group">
                    <label for="demande-postes">Nombre de postes</label>
                    <input type="number" id="demande-postes" name="nombre_postes" min="1" required>
                </div>
                <div class="input-group">
                    <label for="demande-message">Message (facultatif)</label>
                    <textarea id="demande-message" name="message" rows="4" placeholder="Durée, date de démarrage, lieu, horaires..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-bloc">Envoyer la demande</button>
                <p class="form-mention">Vos informations sont utilisées uniquement pour traiter votre demande. <a href="<?= e($lien_confidentialite) ?>">En savoir plus sur la protection de vos données</a>.</p>
            </form>
        </div>
    </div>

    <!-- Tiroir "Créer mon profil" (candidat) -> api/candidat.php
         enctype="multipart/form-data" est obligatoire pour envoyer un fichier (CV) -->
    <div class="drawer" id="drawer-candidat" role="dialog" aria-modal="true" aria-labelledby="drawer-candidat-titre" tabindex="-1">
        <button type="button" class="modal-close" aria-label="Fermer la fenêtre">&times;</button>
        <div class="drawer-header">
            <h3 id="drawer-candidat-titre">Créer mon profil candidat</h3>
            <p>Rejoignez le vivier de talents CAFPM et soyez contacté pour les missions et les emplois qui correspondent à votre profil.</p>
        </div>
        <div class="drawer-content">
            <form class="interactive-form" action="<?= e(lien_site('api/candidat.php')) ?>" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="candidat-nom">Nom complet</label>
                    <input type="text" id="candidat-nom" name="nom" autocomplete="name" required>
                </div>
                <div class="input-group">
                    <label for="candidat-telephone">Téléphone</label>
                    <input type="tel" id="candidat-telephone" name="telephone" autocomplete="tel" required>
                </div>
                <div class="input-group">
                    <label for="candidat-metier">Métier / Poste</label>
                    <input type="text" id="candidat-metier" name="metier" placeholder="Ex. : Comptable, Agent d'entretien" required>
                </div>
                <div class="input-group">
                    <label for="candidat-domaine">Domaine d'expertise</label>
                    <select id="candidat-domaine" name="domaine" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($domaines as $domaine): ?>
                        <option><?= e($domaine) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="candidat-experience">Expérience</label>
                    <select id="candidat-experience" name="experience" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($niveaux_experience as $niveau): ?>
                        <option><?= e($niveau) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="candidat-disponibilite">Disponibilité</label>
                    <select id="candidat-disponibilite" name="disponibilite" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($disponibilites as $disponibilite): ?>
                        <option><?= e($disponibilite) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="candidat-localisation">Ville / commune</label>
                    <select id="candidat-localisation" name="localisation" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach ($localisations as $groupe => $lieux): ?>
                        <optgroup label="<?= e($groupe) ?>">
                            <?php foreach ($lieux as $lieu): ?>
                            <option><?= e($lieu) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="candidat-cv">Joindre un CV (PDF, 5 Mo max)</label>
                    <input type="file" id="candidat-cv" name="cv" accept=".pdf" required>
                </div>
                <button type="submit" class="btn btn-primary btn-bloc">Créer mon profil</button>
                <p class="form-mention">Votre CV et vos coordonnées sont conservés de façon sécurisée et transmis uniquement aux entreprises concernées par une mission. Dans la recherche de profils du site, seuls votre métier, votre domaine, votre expérience, votre disponibilité et votre ville apparaissent, de façon anonyme. <a href="<?= e($lien_confidentialite) ?>">Politique de confidentialité</a>.</p>
            </form>
        </div>
    </div>

    <!-- Toast Notification : le texte est remplacé par la réponse du serveur.
         main.js ajoute la classe "toast-erreur" (icône et bordure rouges) si succes = false -->
    <div class="toast" id="toast" role="status" aria-live="polite">
        <svg class="toast-icone toast-icone-succes" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <svg class="toast-icone toast-icone-erreur" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span class="toast-message">Action traitée avec succès !</span>
        <button type="button" class="toast-fermer" aria-label="Fermer la notification">&times;</button>
    </div>

    <!-- Toast Notification (Search) -->
    <div class="toast" id="toast-search" role="status" aria-live="polite">
        <svg class="toast-icone toast-icone-succes" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <svg class="toast-icone toast-icone-erreur" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <span class="toast-message">Recherche en cours...</span>
        <button type="button" class="toast-fermer" aria-label="Fermer la notification">&times;</button>
    </div>
