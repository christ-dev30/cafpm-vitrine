<?php
/* ==========================================================================
   BACK-OFFICE : TABLEAU DE BORD
   --------------------------------------------------------------------------
   Pensé comme une file de travail : ce qu'il faut traiter en premier.
   1. Chiffres clés (une seule ligne, chaque chiffre mène à sa liste)
   2. Demandes de personnel pas encore traitées (les plus récentes)
   3. Messages de contact non lus
   4. Derniers candidats inscrits
   ========================================================================== */

require_once __DIR__ . '/../includes/auth-admin.php';
require_once __DIR__ . '/../includes/layout-admin.php';

$admin = exiger_admin();

$chiffres = $demandes = $messages = $candidats = [];

try {
    $pdo = db();

    // 1. Chiffres clés : [libellé, nombre, lien]
    $chiffres = [
        ['Nouvelles demandes',    "SELECT COUNT(*) FROM demandes WHERE statut = 'nouvelle'",    'demandes.php?statut=nouvelle'],
        ['Demandes en cours',     "SELECT COUNT(*) FROM demandes WHERE statut = 'en_cours'",    'demandes.php?statut=en_cours'],
        ['Candidats disponibles', "SELECT COUNT(*) FROM candidats WHERE statut = 'disponible'", 'candidats.php?statut=disponible'],
        ['Messages non lus',      'SELECT COUNT(*) FROM messages WHERE lu = 0',                 'messages.php?filtre=non_lus'],
        ['Inscrits newsletter',   'SELECT COUNT(*) FROM newsletter',                            'newsletter.php'],
    ];
    foreach ($chiffres as $i => [$libelle, $sql, $lien]) {
        $chiffres[$i][1] = (int) $pdo->query($sql)->fetchColumn();
    }

    // 2. Demandes à traiter (nouvelles d'abord, puis en cours)
    $demandes = $pdo->query(
        "SELECT id, entreprise, profil, secteur, nombre_postes, statut, cree_le
         FROM demandes WHERE statut <> 'traitee'
         ORDER BY statut = 'nouvelle' DESC, cree_le DESC LIMIT 6"
    )->fetchAll();

    // 3. Messages non lus
    $messages = $pdo->query(
        'SELECT id, nom, sujet, cree_le FROM messages WHERE lu = 0 ORDER BY cree_le DESC LIMIT 5'
    )->fetchAll();

    // 4. Derniers candidats
    $candidats = $pdo->query(
        'SELECT id, metier, domaine, localisation, statut, cree_le FROM candidats ORDER BY cree_le DESC, id DESC LIMIT 5'
    )->fetchAll();
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur tableau de bord admin : ' . $erreur->getMessage());
    flash('erreur', 'Impossible de charger le tableau de bord.');
}

admin_entete('Tableau de bord', 'accueil', $admin);
?>
        <p class="admin-date"><?= e(ucfirst(date_du_jour())) ?></p>

        <!-- 1. Chiffres clés -->
        <div class="chiffres-bande">
            <?php foreach ($chiffres as [$libelle, $nombre, $lien]): ?>
                <a href="<?= e($lien) ?>" class="chiffre<?= $nombre > 0 && str_contains($lien, 'nouvelle') ? ' chiffre-alerte' : '' ?>">
                    <strong><?= $nombre ?></strong>
                    <span><?= e($libelle) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="tdb-grille">
            <!-- 2. Demandes à traiter -->
            <section class="panneau tdb-principal">
                <header class="panneau-entete">
                    <h2>Demandes à traiter</h2>
                    <a href="demandes.php">Toutes les demandes &rarr;</a>
                </header>
                <?php if (!$demandes): ?>
                    <p class="panneau-vide">Rien en attente. Toutes les demandes ont été traitées.</p>
                <?php else: ?>
                <ul class="liste-simple">
                    <?php foreach ($demandes as $d): ?>
                    <li>
                        <a href="demandes.php<?= $d['statut'] === 'nouvelle' ? '?statut=nouvelle' : '?statut=en_cours' ?>" class="ligne">
                            <span class="ligne-principal">
                                <strong><?= e($d['entreprise']) ?></strong>
                                <span><?= (int) $d['nombre_postes'] ?> &times; <?= e($d['profil']) ?> &middot; <?= e($d['secteur']) ?></span>
                            </span>
                            <span class="ligne-meta">
                                <span class="badge badge-<?= e($d['statut']) ?>"><?= e(libelle_statut($d['statut'])) ?></span>
                                <time datetime="<?= e($d['cree_le']) ?>"><?= e(il_y_a($d['cree_le'])) ?></time>
                            </span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </section>

            <div class="tdb-cote">
                <!-- 3. Messages non lus -->
                <section class="panneau">
                    <header class="panneau-entete">
                        <h2>Messages non lus</h2>
                        <a href="messages.php">Voir &rarr;</a>
                    </header>
                    <?php if (!$messages): ?>
                        <p class="panneau-vide">Aucun message en attente.</p>
                    <?php else: ?>
                    <ul class="liste-simple">
                        <?php foreach ($messages as $m): ?>
                        <li>
                            <a href="messages.php?filtre=non_lus" class="ligne">
                                <span class="ligne-principal">
                                    <strong><?= e($m['nom']) ?></strong>
                                    <span><?= e($m['sujet']) ?></span>
                                </span>
                                <time class="ligne-meta" datetime="<?= e($m['cree_le']) ?>"><?= e(il_y_a($m['cree_le'])) ?></time>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </section>

                <!-- 4. Derniers candidats -->
                <section class="panneau">
                    <header class="panneau-entete">
                        <h2>Derniers candidats</h2>
                        <a href="candidats.php">CVthèque &rarr;</a>
                    </header>
                    <?php if (!$candidats): ?>
                        <p class="panneau-vide">Aucun candidat inscrit pour le moment.</p>
                    <?php else: ?>
                    <ul class="liste-simple">
                        <?php foreach ($candidats as $c): ?>
                        <li>
                            <a href="candidats.php?q=<?= e(urlencode($c['metier'])) ?>" class="ligne">
                                <span class="ligne-principal">
                                    <strong><?= e($c['metier']) ?></strong>
                                    <span><?= e($c['domaine']) ?><?= $c['localisation'] ? ' &middot; ' . e($c['localisation']) : '' ?></span>
                                </span>
                                <time class="ligne-meta" datetime="<?= e($c['cree_le']) ?>"><?= e(il_y_a($c['cree_le'])) ?></time>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </section>
            </div>
        </div>
<?php
admin_pied();
