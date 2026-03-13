import './stimulus_bootstrap.js';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

window.onload = () => {
    // Récupération du bouton du menu burger et de la liste du menu
    const navButton = document.querySelector("#main-navbar .nav-button");
    const navMenu = document.querySelector("#main-navbar .nav-menu");

    // -------------------------------------------------
    // Ouvrir / fermer le menu au clic sur le bouton
    // -------------------------------------------------
    navButton.addEventListener("click", function(event){
        event.stopPropagation(); // Empêche la propagation pour que le document n'écoute pas ce clic

        // Toggle la classe 'show' sur le menu pour l'afficher ou le cacher
        navMenu.classList.toggle("show");

        // Toggle la classe 'menu-open' sur le body pour bloquer le scroll
        document.body.classList.toggle("menu-open");
    });

    // -------------------------------------------------
    // Fermer le menu si on clique ailleurs sur la page
    // -------------------------------------------------
    document.addEventListener("click", function(){
        navMenu.classList.remove("show");          // On cache le menu
        document.body.classList.remove("menu-open"); // On réactive le scroll
    });

    // -------------------------------------------------
    // Fermer le menu si on clique sur un lien du menu
    // -------------------------------------------------
    navMenu.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            navMenu.classList.remove("show");          // On cache le menu
            document.body.classList.remove("menu-open"); // On réactive le scroll
        });
    });
}