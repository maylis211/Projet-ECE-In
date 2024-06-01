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

$(document).ready(function() {
    // Gestion du like
    $(".like-button").click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton

        // Récupère l'ID du post à liker
        var post_id = $(this).siblings("input[name='post_id']").val();

        // Envoie une requête AJAX pour liker le post
        $.ajax({
            type: "POST",
            url: "accueil.php",
            data: {
                like: true,
                post_id: post_id
            },
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    alert(response.error); // Affiche l'erreur s'il y en a une
                } else {
                    // Met à jour le nombre de likes affiché
                    $(event.target).text('Like (' + response.likes + ')');
                    alert(response.success); // Affiche le message de succès
                }
            },
            error: function(xhr, status, error) {
                alert("Une erreur s'est produite lors de l'envoi de la requête.");
                console.error(xhr.responseText); // Affiche les détails de l'erreur dans la console
            }
        });
    });

    // Gestion du partage
    $(".share-button").click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du bouton
        
        // Affiche la boîte de dialogue de partage
        $("#shareModal").css("display", "block");
    });

    // Ferme la boîte de dialogue de partage lorsque l'utilisateur clique sur le bouton de fermeture
    $(".close").click(function() {
        $("#shareModal").css("display", "none");
    });

    // Gère le partage lorsque l'utilisateur clique sur le bouton "Partager"
    $("#confirmShare").click(function() {
        // Récupère les comptes sélectionnés à partager
        var accounts = [];
        $("input[type='checkbox']:checked").each(function() {
            accounts.push($(this).attr("name"));
        });

        // Effectue les actions nécessaires pour partager avec les comptes sélectionnés
        // Par exemple, vous pouvez envoyer une requête AJAX pour partager avec les comptes sélectionnés
        console.log("Partage avec les comptes suivants :", accounts);

        // Ferme la boîte de dialogue de partage après le partage
        $("#shareModal").css("display", "none");
    });
});
