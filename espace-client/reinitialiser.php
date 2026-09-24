<?php
/* ==========================================================================
   ESPACE CLIENT : CHOISIR UN NOUVEAU MOT DE PASSE (lien reçu par email)
   --------------------------------------------------------------------------
   Adresse : espace-client/reinitialiser.php?jeton=...
   1. Vérifie le jeton : existe, pas encore utilisé, moins d'une heure
   2. À l'envoi (POST) : jeton CSRF, contrôle du mot de passe, enregistrement
      du nouveau hash, et le lien devient inutilisable
   3. Affiche selon le cas : formulaire, lien invalide, ou succès
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';
require_once __DIR__ . '/../includes/layout-espace.php';

/**
 * Renvoie la demande de réinitialisation valide correspondant au jeton, ou null.
 */
function trouver_reinitialisation(string $jeton): ?array
{
    if (!preg_match('/^[a-f0-9]{64}$/', $jeton)) {
        return null; // Format incorrect : inutile d'interroger la base
    }
    $requete = db()->prepare(
        'SELECT id, client_id FROM reinitialisations
         WHERE jeton_hash = ? AND utilise = 0 AND expire_le > NOW() LIMIT 1'
    );
    $requete->execute([hash('sha256', $jeton)]);
    return $requete->fetch() ?: null;
}

$termine = isset($_GET['termine']);
$jeton   = $_POST['jeton'] ?? $_GET['jeton'] ?? '';
$jeton   = is_string($jeton) ? $jeton : '';

try {
    // 1. Vérification du jeton
    $demande = $termine ? null : trouver_reinitialisation($jeton);

    // 2. Traitement du formulaire
    if ($demande && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nouveau      = mot_de_passe_post('nouveau');
        $confirmation = mot_de_passe_post('confirmation');

        if (!csrf_valide()) {
            flash('erreur', 'Session expirée. Veuillez renvoyer le formulaire.');
        } elseif ($erreur_mdp = erreur_mot_de_passe($nouveau, $confirmation)) {
            flash('erreur', $erreur_mdp);
        } else {
            db()->prepare('UPDATE clients SET mot_de_passe = ? WHERE id = ?')
                ->execute([password_hash($nouveau, PASSWORD_DEFAULT), $demande['client_id']]);
            // Ce lien et tous les autres liens de ce client deviennent inutilisables
            db()->prepare('UPDATE reinitialisations SET utilise = 1 WHERE client_id = ?')
                ->execute([$demande['client_id']]);

            rediriger('reinitialiser.php?termine=1');
        }
    }
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur réinitialisation : ' . $erreur->getMessage());
    $demande = null;
    flash('erreur', 'Une erreur est survenue. Veuillez réessayer plus tard.');
}

// 3. Affichage
espace_entete('Nouveau mot de passe');
?>
        <section class="espace-carte espace-carte-etroite">
            <?php if ($termine): ?>
                <h1>Mot de passe modifié</h1>
                <p class="espace-texte-doux">Votre nouveau mot de passe est enregistré. Vous pouvez maintenant
                    vous connecter depuis le bouton « Espace client » du site.</p>
                <div class="espace-actions">
                    <a href="../index.php" class="btn btn-primary">Aller au site pour me connecter</a>
                </div>

            <?php elseif (!$demande): ?>
                <h1>Lien invalide ou expiré</h1>
                <p class="espace-texte-doux">Ce lien de réinitialisation n'est plus valable (il expire après 1 heure
                    et ne peut servir qu'une fois). Vous pouvez en demander un nouveau.</p>
                <div class="espace-actions">
                    <a href="mot-de-passe-oublie.php" class="btn btn-primary">Demander un nouveau lien</a>
                </div>

            <?php else: ?>
                <h1>Choisir un nouveau mot de passe</h1>
                <p class="espace-texte-doux">8 caractères minimum.</p>
                <form method="post" action="reinitialiser.php" class="espace-form">
                    <?= champ_csrf() ?>
                    <input type="hidden" name="jeton" value="<?= e($jeton) ?>">
                    <div class="input-group">
                        <label for="nouveau">Nouveau mot de passe</label>
                        <input type="password" id="nouveau" name="nouveau" required minlength="8" maxlength="72" autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="confirmation">Confirmer le mot de passe</label>
                        <input type="password" id="confirmation" name="confirmation" required minlength="8" maxlength="72" autocomplete="new-password">
                    </div>
                    <div class="espace-actions">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
<?php
espace_pied();
