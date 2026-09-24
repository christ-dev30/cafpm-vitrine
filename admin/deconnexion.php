<?php
/* ==========================================================================
   BACK-OFFICE : DÉCONNEXION
   --------------------------------------------------------------------------
   1. Vérifie POST + jeton CSRF (sinon retour au tableau de bord)
   2. Détruit la session (données, cookie)
   3. Retour à la page de connexion admin
   ========================================================================== */

require_once __DIR__ . '/../includes/fonctions.php';

// 1. Sécurité
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valide()) {
    rediriger('index.php');
}

// 2. Destruction de la session
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

// 3. Retour à la connexion
rediriger('connexion.php');
