<?php
/* ==========================================================================
   TRAITEMENT : barre de recherche "Trouvez le profil qu'il vous faut"
   --------------------------------------------------------------------------
   1. Récupère les critères (tous facultatifs) : mot-clé, secteur,
      expérience, disponibilité, localisation
   2. Ne cherche que parmi les candidats au statut "disponible"
      (statut géré dans l'admin : admin/candidats.php)
   3. Renvoie le NOMBRE de profils trouvés + les 12 plus récents
   CONFIDENTIALITÉ : les profils sont ANONYMES. On n'envoie jamais le nom,
   le téléphone ni le CV : seulement n° de profil, métier, domaine,
   expérience, disponibilité et ville. L'entreprise demande ensuite le profil
   via "Déposer un besoin" et CAFPM fait la mise en relation.
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';
require_once __DIR__ . '/../config/contenu.php'; // Listes des filtres

exiger_post();
verifier_csrf();

const PROFILS_AFFICHES = 12; // Nombre maximum de profils renvoyés

// 1. Critères de recherche (une valeur hors liste est simplement ignorée)
$mot_cle       = champ('q', 100);
$secteur       = champ('secteur', 100);
$experience    = champ('experience', 30);
$disponibilite = champ('disponibilite', 30);
$localisation  = champ('localisation', 100);

// 2. Construction de la requête : chaque condition n'est ajoutée que si le critère est rempli
$conditions = ["statut = 'disponible'"];
$params     = [];

if ($mot_cle !== '') {
    $mot_cle_sql  = addcslashes($mot_cle, '%_\\'); // "%" et "_" cherchés tels quels (pas comme jokers SQL)
    $conditions[] = '(metier LIKE ? OR domaine LIKE ?)';
    array_push($params, "%$mot_cle_sql%", "%$mot_cle_sql%");
}
if (in_array($secteur, $domaines, true)) {
    $conditions[] = 'domaine = ?';
    $params[]     = $secteur;
}
// Expérience : le niveau choisi OU plus (ex. "3 à 5 ans" inclut "Plus de 5 ans")
$rang = array_search($experience, $niveaux_experience, true);
if ($rang !== false) {
    $niveaux      = array_slice($niveaux_experience, $rang);
    $conditions[] = 'experience IN (' . implode(',', array_fill(0, count($niveaux), '?')) . ')';
    array_push($params, ...$niveaux);
}
// Disponibilité : le délai choisi OU plus rapide (ex. "Sous 15 jours" inclut "Immédiate")
$rang = array_search($disponibilite, $disponibilites, true);
if ($rang !== false) {
    $delais       = array_slice($disponibilites, 0, $rang + 1);
    $conditions[] = 'disponibilite IN (' . implode(',', array_fill(0, count($delais), '?')) . ')';
    array_push($params, ...$delais);
}
if (in_array($localisation, toutes_localisations(), true)) {
    $conditions[] = 'localisation = ?';
    $params[]     = $localisation;
}

$where = 'WHERE ' . implode(' AND ', $conditions);

// 3. Nombre total + profils anonymes (les plus récents d'abord)
try {
    $requete = db()->prepare("SELECT COUNT(*) FROM candidats $where");
    $requete->execute($params);
    $total = (int) $requete->fetchColumn();

    $requete = db()->prepare(
        "SELECT id, metier, domaine, experience, disponibilite, localisation
         FROM candidats $where
         ORDER BY cree_le DESC, id DESC
         LIMIT " . PROFILS_AFFICHES
    );
    $requete->execute($params);
    $profils = $requete->fetchAll();
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur recherche : ' . $erreur->getMessage());
    repondre_json(false, 'Une erreur est survenue. Veuillez réessayer plus tard.', 500);
}

// Champs non renseignés (anciens profils) : libellé neutre
$profils = array_map(fn($p) => [
    'id'            => (int) $p['id'],
    'metier'        => $p['metier'],
    'domaine'       => $p['domaine'],
    'experience'    => $p['experience'] ?: 'Non précisée',
    'disponibilite' => $p['disponibilite'] ?: 'À confirmer',
    'localisation'  => $p['localisation'] ?: 'Non précisée',
], $profils);

$message = $total === 0
    ? 'Aucun profil disponible pour ces critères. Déposez votre besoin, nous le trouverons pour vous !'
    : $total . ' profil' . ($total > 1 ? 's' : '') . ' disponible' . ($total > 1 ? 's' : '') . '.';

repondre_json(true, $message, 200, ['total' => $total, 'profils' => $profils]);
