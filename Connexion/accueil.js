$(document).ready(function(){
    // Initialiser le carrousel
    $('.slick-carousel').slick({
        autoplay: true,
        autoplaySpeed: 1000, // Vitesse de défilement en millisecondes (ms)
        dots: true, // Afficher les points de navigation
        arrows: false, // Masquer les flèches de navigation
        infinite: true,
        speed: 500, // Vitesse de transition en millisecondes (ms)
        slidesToShow: 1, // Nombre de diapositives à afficher à la fois
        slidesToScroll: 1 // Nombre de diapositives à faire défiler à chaque fois
    });
});

$(document).ready(function(){
    $('.like-button').click(function(){
        var post_id = $(this).siblings('input[name="post_id"]').val();
        $.ajax({
            type: 'POST',
            url: 'like.php', // Remplacez 'like.php' par le nom de votre script PHP pour traiter le like
            data: {post_id: post_id},
            success: function(response) {
                // Mettez à jour le compteur de likes sans recharger la page
                $(this).siblings('.like-count').html('Likes: ' + response);
            }
        });
    });
});
