<?php
/* ==========================================================================
   ESPACE CLIENT : DÉCONNEXION
   --------------------------------------------------------------------------
   Appelée par le bouton "Déconnexion" (formulaire POST + jeton CSRF, pour
   qu'un autre site ne puisse pas déconnecter le client à son insu).
   1. Vérifie POST + jeton CSRF (sinon retour au tableau de bord)
   2. Détruit la session (client oublié, nouveaux identifiant et jeton)
   3. Redirige vers la page d'accueil du site
   ========================================================================== */

require_once __DIR__ . '/../includes/fonctions.php';

// 1. Sécurité
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valide()) {
    rediriger('index.php');
}

// 2. Destruction de la session : données vidées, cookie supprimé, session effacée
$_SESSION = [];
$cookie = session_get_cookie_params();
setcookie(session_name(), '', [
    'expires'  => time() - 3600,
    'path'     => $cookie['path'],
    'domain'   => $cookie['domain'],
    'secure'   => $cookie['secure'],
    'httponly' => $cookie['httponly'],
    'samesite' => $cookie['samesite'] ?: 'Lax',
]);
session_destroy();

// 3. Retour à l'accueil
rediriger('../index.php');
