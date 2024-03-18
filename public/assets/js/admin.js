(function() {
    const formsOfSuppr = document.querySelectorAll(".article_delete_form");
    formsOfSuppr.forEach(form => {
        form.addEventListener("click", confSuppr);
    });

    /**
     * Permet de confirmer une action.
     * @param {PointerEvent} e preventDefault() si confirm = false, si true ne fait rien en retournant false.
     */
    function confSuppr(e) {
        return !confirm("Confirmez vous vouloir effectué cette action?") ? e.preventDefault() : false;
    }
}())