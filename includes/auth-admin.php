<?php
/* ==========================================================================
   GARDE D'ACCÈS DU BACK-OFFICE (dossier admin/)
   --------------------------------------------------------------------------
   À placer en haut de chaque page d'administration (sauf connexion.php) :
       require_once __DIR__ . '/../includes/auth-admin.php';
       $admin = exiger_admin();   // ['id', 'nom', 'email']
   - Personne de connecté → renvoi vers admin/connexion.php
   - Plus de 2 heures sans activité → déconnexion automatique
   - Compte administrateur supprimé entre-temps → déconnexion
   ========================================================================== */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/fonctions.php';

const ADMIN_INACTIVITE_MAX = 2 * 60 * 60; // 2 heures (en secondes)

function exiger_admin(): array
{
    $admin = null;

    // 1. Connecté et actif récemment ?
    if (!empty($_SESSION['admin_id'])) {
        if (time() - (int) ($_SESSION['admin_activite'] ?? 0) > ADMIN_INACTIVITE_MAX) {
            unset($_SESSION['admin_id'], $_SESSION['admin_activite']);
            flash('info', 'Session expirée après une longue inactivité. Reconnectez-vous.');
        } else {
            // 2. Le compte existe toujours ?
            try {
                $requete = db()->prepare('SELECT id, nom, email FROM admins WHERE id = ? LIMIT 1');
                $requete->execute([(int) $_SESSION['admin_id']]);
                $admin = $requete->fetch() ?: null;
            } catch (PDOException $erreur) {
                error_log('[CAFPM] Erreur garde admin : ' . $erreur->getMessage());
                http_response_code(500);
                exit('Service momentanément indisponible. Réessayez plus tard.');
            }
        }
    }

    // 3. Pas d'accès : retour à la page de connexion
    if (!$admin) {
        unset($_SESSION['admin_id'], $_SESSION['admin_activite']);
        rediriger('connexion.php');
    }

    $_SESSION['admin_activite'] = time(); // Mémorise la dernière activité
    return $admin;
}
