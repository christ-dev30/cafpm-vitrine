<?php
/* ==========================================================================
   TRAITEMENT : "Créer mon profil" (formulaire candidat + CV)
   --------------------------------------------------------------------------
   1. Vérifie la sécurité (POST + jeton CSRF)
   2. Récupère et contrôle les champs
   3. Vérifie le CV (PDF, taille max) puis l'enregistre dans uploads/cv/
   4. Enregistre le candidat dans la table `candidats`
   5. Prévient CAFPM par email
   6. Répond au JavaScript en JSON
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';
require_once __DIR__ . '/../config/contenu.php'; // Listes expérience / disponibilité / localisation

// 1. Sécurité
exiger_post();
verifier_csrf();

// 2. Récupération des champs
$nom       = champ('nom', 150);
$telephone = champ('telephone', 30);
$metier    = champ('metier', 150);
$domaine   = champ('domaine', 100);
$experience    = champ('experience', 30);
$disponibilite = champ('disponibilite', 30);
$localisation  = champ('localisation', 100);

if ($nom === '' || $telephone === '' || $metier === '' || $domaine === '') {
    repondre_json(false, 'Merci de remplir tous les champs obligatoires.', 422);
}

// Les 3 critères de recherche doivent correspondre exactement aux listes du site
if (!in_array($experience, $niveaux_experience, true)
    || !in_array($disponibilite, $disponibilites, true)
    || !in_array($localisation, toutes_localisations(), true)) {
    repondre_json(false, 'Merci de préciser votre expérience, votre disponibilité et votre ville.', 422);
}

// 3. Contrôle du CV
$cv = $_FILES['cv'] ?? null;

// Fichier plus gros que la limite du serveur (upload_max_filesize, voir .user.ini)
if ($cv && in_array($cv['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
    repondre_json(false, 'Le CV ne doit pas dépasser 5 Mo.', 422);
}
if (!$cv || $cv['error'] !== UPLOAD_ERR_OK) {
    repondre_json(false, 'Veuillez joindre votre CV au format PDF.', 422);
}
if ($cv['size'] > TAILLE_MAX_CV) {
    repondre_json(false, 'Le CV ne doit pas dépasser 5 Mo.', 422);
}

// On vérifie le VRAI type du fichier (et pas seulement son extension)
$type_reel = (new finfo(FILEINFO_MIME_TYPE))->file($cv['tmp_name']);
if ($type_reel !== 'application/pdf') {
    repondre_json(false, 'Seuls les fichiers PDF sont acceptés.', 422);
}

// Nom de fichier unique et aléatoire (évite les doublons et les noms dangereux)
$nom_fichier = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.pdf';

if (!is_dir(DOSSIER_CV)) {
    mkdir(DOSSIER_CV, 0755, true);
}
if (!move_uploaded_file($cv['tmp_name'], DOSSIER_CV . $nom_fichier)) {
    repondre_json(false, "Impossible d'enregistrer le CV. Réessayez plus tard.", 500);
}

// 4. Enregistrement en base de données
try {
    $requete = db()->prepare(
        'INSERT INTO candidats (nom, telephone, metier, domaine, experience, disponibilite, localisation, fichier_cv)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $requete->execute([$nom, $telephone, $metier, $domaine, $experience, $disponibilite, $localisation, $nom_fichier]);
} catch (PDOException $erreur) {
    @unlink(DOSSIER_CV . $nom_fichier); // On supprime le CV si l'enregistrement a échoué
    error_log('[CAFPM] Erreur candidat : ' . $erreur->getMessage());
    repondre_json(false, 'Une erreur est survenue. Veuillez réessayer plus tard.', 500);
}

// 5. Notification email
envoyer_email(
    'Nouveau candidat : ' . $nom,
    "Nom : $nom\nTéléphone : $telephone\nMétier : $metier\nDomaine : $domaine\n"
    . "Expérience : $experience\nDisponibilité : $disponibilite\nVille : $localisation\n"
    . "CV : uploads/cv/$nom_fichier"
);

// 6. Réponse
repondre_json(true, 'Profil créé avec succès ! Nous vous contacterons bientôt.');
