$(document).ready(function(){
    // Initialiser le carrousel
    $('.slick-carousel').slick({
        autoplay: true,
        autoplaySpeed: 3000, // Vitesse de défilement en millisecondes (ms)
        dots: true, // Afficher les points de navigation
        arrows: false, // Masquer les flèches de navigation
        infinite: true,
        speed: 500, // Vitesse de transition en millisecondes (ms)
        slidesToShow: 1, // Nombre de diapositives à afficher à la fois
        slidesToScroll: 1 // Nombre de diapositives à faire défiler à chaque fois
    });
});

