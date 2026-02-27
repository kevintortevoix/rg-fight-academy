import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

window.onload = () => {
    const navButton = document.querySelector("#main-navbar .nav-button");
    const navMenu = document.querySelector("#main-navbar .nav-menu");

    // Si on clique sur le bouton on ouvre le menu
    navButton.addEventListener("click", function(event){
        // On stoppe la propagation
        event.stopPropagation();

        // On ajoute la classe show ou on l'enlève
        navMenu.classList.toggle("show");
        document.body.classList.toggle("menu-open");
    });

    // Si on clique n'importe ou le menu se ferme
    document.addEventListener("click", function(){
        navButton.classList.remove("show");
        document.body.classList.remove("menu-open");
    })
}