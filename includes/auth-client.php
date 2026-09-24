<?php
/* ==========================================================================
   GARDE D'ACCÈS DE L'ESPACE CLIENT
   --------------------------------------------------------------------------
   À placer en haut de chaque page réservée aux clients connectés :
       require_once __DIR__ . '/../includes/auth-client.php';
       $client = exiger_client();   // ['id', 'entreprise', 'email']
   Si personne n'est connecté (ou si le compte a été supprimé entre-temps),
   le visiteur est renvoyé vers la page d'accueil du site.
   ========================================================================== */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/fonctions.php';

function exiger_client(): array
{
    $client = null;

    if (!empty($_SESSION['client_id'])) {
        try {
            // On relit le compte à chaque page : un compte supprimé par l'admin est aussitôt déconnecté
            $requete = db()->prepare('SELECT id, entreprise, email FROM clients WHERE id = ? LIMIT 1');
            $requete->execute([(int) $_SESSION['client_id']]);
            $client = $requete->fetch() ?: null;
        } catch (PDOException $erreur) {
            error_log('[CAFPM] Erreur espace client : ' . $erreur->getMessage());
            http_response_code(500);
            exit('Service momentanément indisponible. Réessayez plus tard.');
        }
    }

    if (!$client) {
        unset($_SESSION['client_id'], $_SESSION['client_entreprise']);
        rediriger('../index.php');
    }

    return $client;
}
