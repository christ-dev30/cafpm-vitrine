<?php
/* ==========================================================================
   PROTECTION ANTI-FORCE BRUTE (essais de mots de passe à répétition)
   --------------------------------------------------------------------------
   Principe : chaque échec est noté dans la table `tentatives` avec l'adresse
   IP du visiteur et l'email saisi. Au-delà de TENTATIVES_MAX échecs en
   TENTATIVES_MINUTES minutes pour le même couple IP + email, on bloque
   temporairement (le blocage se lève tout seul ensuite).

   Utilisation (voir api/connexion.php) :
       if (trop_de_tentatives('client', $email)) { ... bloquer ... }
       noter_echec('client', $email);       // après un mauvais mot de passe
       effacer_tentatives('client', $email); // après une connexion réussie

   $espace distingue les usages : 'client', 'admin', 'oubli'.
   ========================================================================== */

require_once __DIR__ . '/db.php';

const TENTATIVES_MAX     = 5;   // Nombre d'échecs autorisés...
const TENTATIVES_MINUTES = 15;  // ... sur cette durée (en minutes)

/**
 * Adresse IP du visiteur (REMOTE_ADDR : la seule qui ne peut pas être falsifiée).
 */
function ip_visiteur(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? 'inconnue'), 0, 45);
}

/**
 * true si ce couple IP + email a déjà atteint la limite d'échecs.
 */
function trop_de_tentatives(string $espace, string $email): bool
{
    // Ménage : les traces de plus de 24 h sont supprimées (voir confidentialite.php)
    db()->exec('DELETE FROM tentatives WHERE cree_le < NOW() - INTERVAL 1 DAY');

    $requete = db()->prepare(
        'SELECT COUNT(*) FROM tentatives
         WHERE espace = ? AND ip = ? AND email = ?
           AND cree_le > NOW() - INTERVAL ' . TENTATIVES_MINUTES . ' MINUTE'
    );
    $requete->execute([$espace, ip_visiteur(), mb_strtolower($email)]);

    return (int) $requete->fetchColumn() >= TENTATIVES_MAX;
}

/**
 * Enregistre un échec (et fait le ménage des lignes de plus d'un jour).
 */
function noter_echec(string $espace, string $email): void
{
    $pdo = db();
    $pdo->prepare('INSERT INTO tentatives (espace, ip, email) VALUES (?, ?, ?)')
        ->execute([$espace, ip_visiteur(), mb_strtolower($email)]);

    $pdo->exec('DELETE FROM tentatives WHERE cree_le < NOW() - INTERVAL 1 DAY');
}

/**
 * Remet le compteur à zéro (après une connexion réussie).
 */
function effacer_tentatives(string $espace, string $email): void
{
    db()->prepare('DELETE FROM tentatives WHERE espace = ? AND ip = ? AND email = ?')
        ->execute([$espace, ip_visiteur(), mb_strtolower($email)]);
}
