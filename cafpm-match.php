<?php
/* ==========================================================================
   PAGE CAFPM MATCH : présentation de la méthode de rapprochement
   entre les besoins des entreprises et les profils du vivier CAFPM.
   Lien "Découvrir CAFPM Match" de l'accueil (partials/solutions.php).
   ========================================================================== */

require_once __DIR__ . '/partials/amorce.php';

$titre_page       = 'CAFPM Match';
$description_page = "CAFPM Match : la méthode de CAFPM pour rapprocher les besoins des entreprises et les profils qualifiés de son vivier de candidats.";
$page_active      = 'solutions';

$bandeau = [
    'fil'      => [['Accueil', 'index.php'], ['Nos solutions', 'index.php#solutions'], ['CAFPM Match', null]],
    'surtitre' => 'CAFPM Match',
    'titre'    => 'La bonne personne, au bon poste',
    'texte'    => "CAFPM Match, c'est notre méthode pour rapprocher rapidement les besoins des entreprises et les compétences des candidats de notre vivier.",
    'boutons'  => [
        ['libelle' => 'Je recherche du personnel', 'cible' => 'drawer-form', 'style' => 'primary'],
        ['libelle' => 'Je cherche un emploi', 'cible' => 'drawer-candidat', 'style' => 'secondary'],
    ],
];

// Critères croisés pour chaque rapprochement (les mêmes que la barre de recherche)
$criteres = [
    ['Métier et compétences', 'Le poste à pourvoir, les savoir-faire techniques et les qualités attendues.'],
    ["Secteur d'activité", "L'environnement de travail : administration, commerce, entretien, logistique…"],
    ['Expérience', 'Du profil débutant motivé au professionnel confirmé, selon vos exigences.'],
    ['Disponibilité', 'Une prise de poste immédiate, sous quinze jours ou sous un mois.'],
    ['Localisation', 'La commune ou la ville de la mission, pour limiter les temps de trajet.'],
];

require __DIR__ . '/partials/header.php';
?>
    <main id="contenu">
        <?php require __DIR__ . '/partials/bandeau-page.php'; ?>

        <section class="page-section">
            <div class="container grille-intro">
                <div class="prose">
                    <h2>Comment ça fonctionne ?</h2>
                    <p>Chaque candidat qui rejoint CAFPM crée un profil : métier, domaine d'expertise, coordonnées et CV. Nos chargés de recrutement qualifient ensuite ces profils lors d'échanges et d'entretiens, pour connaître précisément les compétences, l'expérience et les disponibilités de chacun.</p>
                    <p>Lorsqu'une entreprise nous confie un besoin, nous croisons ses critères avec les profils de notre vivier de plus de 1 000 candidats. Les profils les plus pertinents sont alors étudiés par un conseiller, qui vérifie leur disponibilité et leur intérêt avant de vous les présenter.</p>
                    <p>Le résultat : moins de temps passé à trier des candidatures, et des propositions ciblées, validées par un regard humain.</p>
                </div>
                <aside class="carte carte-accent">
                    <h2>Les critères croisés</h2>
                    <ul class="liste-criteres">
                        <?php foreach ($criteres as [$critere, $explication]): ?>
                        <li><strong><?= e($critere) ?></strong><span><?= e($explication) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="page-section page-section-blanche">
            <div class="container grille-deux">
                <div class="carte">
                    <span class="section-eyebrow">Vous êtes une entreprise</span>
                    <h2>Recevez des profils ciblés</h2>
                    <ol class="etapes etapes-compactes">
                        <li class="etape"><h3>Décrivez votre besoin</h3><p>Poste, nombre de personnes, lieu, durée et date de démarrage.</p></li>
                        <li class="etape"><h3>Nous rapprochons les profils</h3><p>Nous identifions les candidats correspondants et vérifions leur disponibilité.</p></li>
                        <li class="etape"><h3>Vous choisissez</h3><p>Vous recevez une sélection argumentée et rencontrez les candidats retenus.</p></li>
                    </ol>
                    <button type="button" class="btn btn-primary modal-trigger" data-target="drawer-form">Déposer un besoin &rarr;</button>
                </div>
                <div class="carte">
                    <span class="section-eyebrow">Vous êtes un candidat</span>
                    <h2>Soyez repéré pour les bonnes missions</h2>
                    <ol class="etapes etapes-compactes">
                        <li class="etape"><h3>Créez votre profil</h3><p>Indiquez votre métier, votre domaine et joignez votre CV en PDF.</p></li>
                        <li class="etape"><h3>Échangez avec un conseiller</h3><p>Nous faisons le point sur vos compétences, vos attentes et vos disponibilités.</p></li>
                        <li class="etape"><h3>Recevez des propositions</h3><p>Nous vous contactons dès qu'une mission ou un emploi correspond à votre profil.</p></li>
                    </ol>
                    <button type="button" class="btn btn-secondary modal-trigger" data-target="drawer-candidat">Créer mon profil &rarr;</button>
                </div>
            </div>
        </section>

        <section class="page-section">
            <div class="container conteneur-etroit">
                <div class="section-head">
                    <span class="section-eyebrow">FAQ</span>
                    <h2>Questions fréquentes</h2>
                </div>
                <div class="faq">
                    <details>
                        <summary>CAFPM Match est-il payant pour les candidats ?</summary>
                        <p>Non. La création de votre profil et les propositions de missions ou d'emplois sont gratuites pour les candidats.</p>
                    </details>
                    <details>
                        <summary>Mes informations sont-elles partagées ?</summary>
                        <p>Votre profil n'est transmis qu'aux entreprises concernées par une mission qui vous correspond. Pour en savoir plus, consultez notre <a href="<?= e(lien_site('confidentialite.php')) ?>">politique de confidentialité</a>.</p>
                    </details>
                    <details>
                        <summary>CAFPM Match fonctionne-t-il pour tous les types de contrats ?</summary>
                        <p>Oui : il est utilisé aussi bien pour le <a href="<?= e(lien_site('solution.php?s=travail-temporaire')) ?>">travail temporaire</a> que pour le <a href="<?= e(lien_site('solution.php?s=placement-recrutement')) ?>">placement en CDD ou CDI</a> et la <a href="<?= e(lien_site('solution.php?s=mise-a-disposition')) ?>">mise à disposition de personnel</a>.</p>
                    </details>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/partials/fin-page.php'; ?>
