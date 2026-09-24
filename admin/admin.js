/* ==========================================================================
   JAVASCRIPT DU BACK-OFFICE (chargé par includes/layout-admin.php)
   --------------------------------------------------------------------------
   Aucun script "inline" dans les pages (politique de sécurité CSP) : tout
   le comportement est ici.
   - Formulaires avec l'attribut data-confirm="Question ?" : une fenêtre de
     confirmation s'affiche avant l'envoi (ex. suppression d'un compte).
   ========================================================================== */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) {
                e.preventDefault(); // L'utilisateur a cliqué sur "Annuler"
            }
        });
    });
});
