<?php
/* ==========================================================================
   AMORÇAGE COMMUN DES PAGES PUBLIQUES
   --------------------------------------------------------------------------
   Chargé automatiquement par partials/header.php (et par chaque page).
   Il inclut la configuration, le contenu et les fonctions utilitaires, puis
   définit quelques petites fonctions de navigation :
   - lien_site()      : construit un lien vers un fichier du site, quel que
                        soit le dossier de la page (racine, espace-client/...)
   - lien_section()   : lien vers une section de l'accueil (#solutions...) :
                        "#solutions" sur l'accueil (défilement doux),
                        "index.php#solutions" sur les autres pages
   - aria_page()      : marque le lien du menu correspondant à la page active
   - afficher_blocs() : affiche un texte structuré (paragraphes, titres, listes)

   Variables facultatives à définir AVANT d'inclure partials/header.php :
     $titre_page       : titre de l'onglet (ex. "Contact")
     $description_page : meta description propre à la page
     $page_active      : accueil | solutions | actualites | contact | ...
   ========================================================================== */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/contenu.php';
require_once __DIR__ . '/../includes/fonctions.php';

if (!function_exists('lien_site')) {

    /**
     * Préfixe relatif ("", "../", "../../") entre la page affichée et la
     * racine du site. Permet d'utiliser les partials depuis un sous-dossier.
     */
    function prefixe_racine(): string
    {
        static $prefixe = null;
        if ($prefixe !== null) {
            return $prefixe;
        }

        // Préfixe imposé (ex. par 404.php pour une adresse inconnue)
        if (defined('PREFIXE_RACINE')) {
            return $prefixe = PREFIXE_RACINE;
        }

        $prefixe      = '';
        $racine_site  = realpath(dirname(__DIR__));
        $dossier_page = realpath(dirname($_SERVER['SCRIPT_FILENAME'] ?? ''));

        if ($racine_site && $dossier_page && str_starts_with($dossier_page, $racine_site)) {
            $reste = trim(substr($dossier_page, strlen($racine_site)), '\\/');
            if ($reste !== '') {
                $profondeur = count(preg_split('#[\\\\/]+#', $reste));
                $prefixe    = str_repeat('../', $profondeur);
            }
        }
        return $prefixe;
    }

    /**
     * Lien vers un fichier du site (ex. lien_site('contact.php')).
     */
    function lien_site(string $chemin = ''): string
    {
        $lien = prefixe_racine() . $chemin;
        return $lien === '' ? './' : $lien;
    }

    /**
     * Lien vers une section de la page d'accueil (ex. lien_section('solutions')).
     */
    function lien_section(string $ancre): string
    {
        global $page_active;
        if (($page_active ?? '') === 'accueil') {
            return '#' . $ancre;
        }
        return lien_site('index.php') . '#' . $ancre;
    }

    /**
     * Renvoie l'attribut aria-current="page" si $cle est la page active
     * (le style du lien actif est défini dans style.css).
     */
    function aria_page(string $cle): string
    {
        global $page_active;
        return (($page_active ?? '') === $cle) ? ' aria-current="page"' : '';
    }

    /**
     * Cherche un élément par son slug dans une liste (solutions, actualités).
     * Renvoie null si le slug est inconnu.
     */
    function trouver_par_slug(array $liste, string $slug): ?array
    {
        foreach ($liste as $element) {
            if (($element['slug'] ?? '') === $slug) {
                return $element;
            }
        }
        return null;
    }

    /**
     * Affiche un texte structuré. Chaque bloc est un tableau :
     *   ['p', 'Paragraphe'] | ['h2', 'Intertitre'] | ['ul', ['item 1', 'item 2']]
     * Tout le texte est échappé avec e().
     */
    function afficher_blocs(array $blocs): void
    {
        foreach ($blocs as [$type, $contenu]) {
            if ($type === 'ul') {
                echo "<ul>\n";
                foreach ($contenu as $item) {
                    echo '<li>' . e($item) . "</li>\n";
                }
                echo "</ul>\n";
            } elseif ($type === 'h2' || $type === 'h3') {
                echo '<' . $type . '>' . e($contenu) . '</' . $type . ">\n";
            } else {
                echo '<p>' . e($contenu) . "</p>\n";
            }
        }
    }
}
