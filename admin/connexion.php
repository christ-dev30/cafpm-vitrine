<?php
/* ==========================================================================
   BACK-OFFICE : CONNEXION ADMINISTRATEUR
   --------------------------------------------------------------------------
   1. Déjà connecté ? → tableau de bord
   2. À l'envoi (POST) : jeton CSRF, anti-force brute (5 échecs / 15 min),
      vérification email + mot de passe (password_verify)
   3. Succès : nouvel identifiant de session + mémorisation de l'admin
   Les comptes admin se créent en ligne de commande : admin/creer-admin.php
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';
require_once __DIR__ . '/../includes/anti-force-brute.php';
require_once __DIR__ . '/../includes/layout-admin.php';

// 1. Déjà connecté
if (!empty($_SESSION['admin_id'])) {
    rediriger('index.php');
}

$email = '';

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email        = champ('email', 190);
    $mot_de_passe = mot_de_passe_post('mot_de_passe');

    if (!csrf_valide()) {
        flash('erreur', 'Session expirée. Veuillez renvoyer le formulaire.');
    } else {
        try {
            if (trop_de_tentatives('admin', $email)) {
                flash('erreur', 'Trop de tentatives. Réessayez dans ' . TENTATIVES_MINUTES . ' minutes.');
            } else {
                $requete = db()->prepare('SELECT id, mot_de_passe FROM admins WHERE email = ? LIMIT 1');
                $requete->execute([$email]);
                $admin = $requete->fetch();

                if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
                    // 3. Connexion réussie
                    effacer_tentatives('admin', $email);
                    session_regenerate_id(true);
                    $_SESSION['admin_id']       = (int) $admin['id'];
                    $_SESSION['admin_activite'] = time();
                    rediriger('index.php');
                }

                noter_echec('admin', $email);
                flash('erreur', 'Email ou mot de passe incorrect.');
            }
        } catch (PDOException $erreur) {
            error_log('[CAFPM] Erreur connexion admin : ' . $erreur->getMessage());
            flash('erreur', 'Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }
}

admin_entete('Connexion');
?>
        <section class="admin-connexion">
            <div class="admin-connexion-logo">
                <svg width="44" height="44" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <rect width="40" height="40" fill="#FCE303"/>
                    <path d="M10 28L30 10V22L20 32L10 28Z" fill="white"/>
                </svg>
            </div>
            <h1>Administration <?= e(SITE_NOM) ?></h1>
            <p class="espace-texte-doux">Accès réservé à l'équipe CAFPM.</p>

            <form method="post" action="connexion.php" class="espace-form">
                <?= champ_csrf() ?>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required maxlength="190" value="<?= e($email) ?>" autocomplete="username">
                </div>
                <div class="input-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary btn-large">Se connecter</button>
            </form>
        </section>
<?php
admin_pied();
