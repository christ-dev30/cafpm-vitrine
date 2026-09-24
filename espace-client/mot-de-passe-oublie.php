<?php
/* ==========================================================================
   ESPACE CLIENT : MOT DE PASSE OUBLIÉ (demande du lien)
   --------------------------------------------------------------------------
   1. Le client saisit son email
   2. Si un compte existe : on crée un jeton aléatoire, on stocke seulement
      son empreinte (SHA-256) dans `reinitialisations` (valable 1 heure),
      et on envoie par email le lien espace-client/reinitialiser.php?jeton=...
   3. Le message affiché est TOUJOURS le même, que l'email existe ou non
      (on ne révèle pas quels emails sont clients).
   Limite : 5 demandes / 15 minutes par adresse IP + email (anti-abus).
   MODE DÉVELOPPEMENT : mail() ne fonctionne pas en local, le lien est donc
   affiché directement sur la page (jamais en production).
   ========================================================================== */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/fonctions.php';
require_once __DIR__ . '/../includes/anti-force-brute.php';
require_once __DIR__ . '/../includes/layout-espace.php';

const MESSAGE_OUBLI = "Si un compte existe avec cette adresse, un email contenant un lien de réinitialisation vient d'être envoyé. Le lien est valable 1 heure.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = champ('email', 190);

    if (!csrf_valide()) {
        flash('erreur', 'Session expirée. Veuillez renvoyer le formulaire.');
        rediriger('mot-de-passe-oublie.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('erreur', 'Adresse email invalide.');
        rediriger('mot-de-passe-oublie.php');
    }

    try {
        // Chaque demande compte : au-delà de la limite, on n'envoie plus rien (même message affiché)
        $bloque = trop_de_tentatives('oubli', $email);
        noter_echec('oubli', $email);

        $requete = db()->prepare('SELECT id, entreprise, email FROM clients WHERE email = ? LIMIT 1');
        $requete->execute([$email]);
        $client = $requete->fetch();

        if ($client && !$bloque) {
            // 1. Jeton aléatoire (64 caractères) : seule son empreinte est enregistrée
            $jeton = bin2hex(random_bytes(32));

            // 2. Ménage des liens expirés depuis plus d'un jour (tous clients confondus),
            //    les anciens liens de ce client deviennent inutilisables, puis on enregistre le nouveau
            db()->exec('DELETE FROM reinitialisations WHERE expire_le < NOW() - INTERVAL 1 DAY');
            db()->prepare('UPDATE reinitialisations SET utilise = 1 WHERE client_id = ? AND utilise = 0')
                ->execute([$client['id']]);
            db()->prepare(
                'INSERT INTO reinitialisations (client_id, jeton_hash, expire_le)
                 VALUES (?, ?, NOW() + INTERVAL 1 HOUR)'
            )->execute([$client['id'], hash('sha256', $jeton)]);

            // 3. Envoi du lien par email au client
            $lien = SITE_URL . '/espace-client/reinitialiser.php?jeton=' . $jeton;
            envoyer_email(
                'Réinitialisation de votre mot de passe ' . SITE_NOM,
                "Bonjour {$client['entreprise']},\n\n"
                . "Pour choisir un nouveau mot de passe, ouvrez ce lien (valable 1 heure) :\n$lien\n\n"
                . "Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.\n\n"
                . SITE_NOM . ' ' . SITE_FORME,
                $client['email']
            );

            // Mode développement uniquement : on garde le lien pour l'afficher sur la page
            if (MODE_DEV) {
                $_SESSION['lien_dev'] = $lien;
            }
        }
    } catch (PDOException $erreur) {
        error_log('[CAFPM] Erreur mot de passe oublié : ' . $erreur->getMessage());
        flash('erreur', 'Une erreur est survenue. Veuillez réessayer plus tard.');
        rediriger('mot-de-passe-oublie.php');
    }

    flash('succes', MESSAGE_OUBLI);
    rediriger('mot-de-passe-oublie.php');
}

// Lien de développement à afficher (une seule fois)
$lien_dev = MODE_DEV ? ($_SESSION['lien_dev'] ?? null) : null;
unset($_SESSION['lien_dev']);

espace_entete('Mot de passe oublié');
?>
        <section class="espace-carte espace-carte-etroite">
            <h1>Mot de passe oublié</h1>
            <p class="espace-texte-doux">Saisissez l'adresse email de votre compte client :
                nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>

            <?php if ($lien_dev): ?>
                <div class="dev-box">
                    <strong>Mode développement</strong> &mdash; l'envoi d'emails ne fonctionne pas en local.
                    Lien qui aurait été envoyé par email :
                    <a href="<?= e($lien_dev) ?>"><?= e($lien_dev) ?></a>
                </div>
            <?php endif; ?>

            <form method="post" action="mot-de-passe-oublie.php" class="espace-form">
                <?= champ_csrf() ?>
                <div class="input-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required maxlength="190" placeholder="contact@entreprise.com" autocomplete="email">
                </div>
                <div class="espace-actions">
                    <button type="submit" class="btn btn-primary">Recevoir le lien</button>
                    <a href="../index.php" class="btn btn-secondary">Retour au site</a>
                </div>
            </form>
        </section>
<?php
espace_pied();
