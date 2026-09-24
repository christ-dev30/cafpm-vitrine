<?php
/* ==========================================================================
   BACK-OFFICE : INSCRITS À LA NEWSLETTER
   --------------------------------------------------------------------------
   1. Export CSV (?export=csv) : fichier lisible directement par Excel
      (UTF-8 avec "BOM" pour les accents, séparateur ";" pour Excel français)
   2. Liste paginée des inscrits (50 par page)
   ========================================================================== */

require_once __DIR__ . '/../includes/auth-admin.php';
require_once __DIR__ . '/../includes/layout-admin.php';

$admin = exiger_admin();

// 1. Export CSV de tous les inscrits
if (parametre_get('export', 10) === 'csv') {
    try {
        $inscrits = db()->query('SELECT email, cree_le FROM newsletter ORDER BY cree_le DESC, id DESC');
    } catch (PDOException $erreur) {
        error_log('[CAFPM] Erreur export newsletter : ' . $erreur->getMessage());
        flash('erreur', "Impossible de générer l'export.");
        rediriger('newsletter.php');
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="newsletter-cafpm-' . date('Y-m-d') . '.csv"');
    header('Cache-Control: private, no-store');

    $sortie = fopen('php://output', 'w');
    fwrite($sortie, "\xEF\xBB\xBF"); // BOM : indique à Excel que le fichier est en UTF-8
    fputcsv($sortie, ['Email', "Date d'inscription"], ';');
    foreach ($inscrits as $ligne) {
        // Un email commençant par = + - @ pourrait être pris pour une formule par Excel : on le neutralise
        $email = preg_match('/^[=+\-@]/', $ligne['email']) ? "'" . $ligne['email'] : $ligne['email'];
        fputcsv($sortie, [$email, date_fr($ligne['cree_le'])], ';');
    }
    fclose($sortie);
    exit;
}

// 2. Liste paginée
$inscrits = [];
$p = paginer(0);
try {
    $p = paginer((int) db()->query('SELECT COUNT(*) FROM newsletter')->fetchColumn());
    $inscrits = db()->query(
        "SELECT email, cree_le FROM newsletter ORDER BY cree_le DESC, id DESC
         LIMIT {$p['limite']} OFFSET {$p['decalage']}"
    )->fetchAll();
} catch (PDOException $erreur) {
    error_log('[CAFPM] Erreur liste newsletter : ' . $erreur->getMessage());
    flash('erreur', 'Impossible de charger les inscrits.');
}

admin_entete('Newsletter', 'newsletter', $admin);
?>
        <div class="filtres">
            <span class="filtres-total"><?= $p['total'] ?> inscrit(s)</span>
            <?php if ($p['total'] > 0): ?>
                <a href="?export=csv" class="btn btn-primary btn-petit">Exporter en CSV (Excel)</a>
            <?php endif; ?>
        </div>

        <?php if (!$inscrits): ?>
            <p class="espace-vide">Aucun inscrit pour le moment.</p>
        <?php else: ?>
        <div class="tableau-conteneur" tabindex="0">
            <table class="tableau">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscrits as $i): ?>
                    <tr>
                        <td><a href="mailto:<?= e($i['email']) ?>"><?= e($i['email']) ?></a></td>
                        <td><?= e(date_fr($i['cree_le'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php afficher_pagination($p); ?>
        <?php endif; ?>
<?php
admin_pied();
