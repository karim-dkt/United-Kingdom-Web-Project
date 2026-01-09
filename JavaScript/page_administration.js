document.addEventListener("DOMContentLoaded", function() {
    const blocs = [
        "article-wrapper",
        "modification",
        "news-management-section"
    ];

    const footer = document.getElementById("footer"); // ton footer

    // récupère le dernier bloc actif (stocké en localStorage) ou utilise le premier au démarrage
    let act = localStorage.getItem('lastActiveBlock') || blocs[0];

    function updateFooterMargin() {
    const actif = document.querySelector(".active-block"); // bloc actuellement affiché
    if (!footer) return;

    const minMargin = 200; // marge-top minimale en px (ajuste si besoin)

    if (actif) {
        const marge = actif.offsetHeight - 1100;
        const finalMargin = Math.max(marge, minMargin);
        footer.style.marginTop = finalMargin + "px";
    } else {
        footer.style.marginTop = minMargin + "px";
    }
}

    function showBlock(className) {
        // Masquer tous les blocs et retirer la classe active
        blocs.forEach(nom => {
            document.querySelectorAll("." + nom).forEach(el => {
                el.style.display = "none";
                el.classList.remove("active-block");
            });
        });

        // Afficher le bloc demandé et ajouter la classe active
        const actif = document.querySelector("." + className);
        if (actif) {
            actif.style.display = "block";
            actif.classList.add("active-block");
        }

        // mémorise le bloc affiché
        act = className;
        try { localStorage.setItem('lastActiveBlock', act); } catch(e){/* ignore */ }

        // Mettre à jour le margin-top du footer
        updateFooterMargin();
    }

    // Attacher les clics sur les <li>
    const items = document.querySelectorAll(".position-list li[data-target]");
    items.forEach(li => {
        li.addEventListener("click", function() {
            const target = li.dataset.target;
            showBlock(target);
        });
    });

    // Au départ : afficher le dernier bloc cliqué (ou le premier si absent)
    if (document.querySelector("." + act)) {
        showBlock(act);
    } else {
        showBlock(blocs[0]);
    }

    // Recalculer le margin du footer si la fenêtre est redimensionnée
    window.addEventListener("resize", updateFooterMargin);
});
