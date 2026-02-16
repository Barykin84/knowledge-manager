// Fonction pour afficher/masquer le menu burger
function toggleMenu() {
    var menu = document.getElementById('menu');
    var body = document.body;

    // Ajoute ou retire la classe 'open' au menu pour le rendre visible
    menu.classList.toggle('open');

    // Empêche le défilement de la page quand le menu est ouvert
    body.classList.toggle('menu-open');
}

// Fermer le menu lorsque l'on clique en dehors
document.addEventListener('click', function(event) {
    var menu = document.getElementById('menu');
    var menuToggle = document.querySelector('.menu-toggle');
    
    // Si le clic n'est pas sur le menu ou le bouton burger, fermer le menu
    if (!menu.contains(event.target) && !menuToggle.contains(event.target)) {
        menu.classList.remove('open');
        document.body.classList.remove('menu-open');
    }
});





