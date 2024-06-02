<?php
//L'utilisateur est sur sa session
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Si le formulaire de création de post est soumis
if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $content = $conn->real_escape_string($_POST['content']);
    $date_debut = NULL;
    $date_fin = NULL;
    $is_event = isset($_POST['is_event']) ? 1 : 0; // Vérifie si la case à cocher est cochée

    $date_debut = $is_event ? $conn->real_escape_string($_POST['date_debut']) : NULL;
    $date_fin = $is_event ? $conn->real_escape_string($_POST['date_fin']) : NULL;

    // Initialiser le chemin de l'image et de la vidéo à une valeur vide par défaut
    $media_path = '';

    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
        $allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_video_extensions = ['mp4', 'webm', 'ogg'];

        if (in_array($file_extension, $allowed_image_extensions)) {
            $target_dir = "uploads/images/";
        } elseif (in_array($file_extension, $allowed_video_extensions)) {
            $target_dir = "uploads/videos/";
        } else {
            echo "Type de fichier non supporté.";
            exit;
        }

        $target_file = $target_dir . basename($_FILES['media']['name']);
        if (move_uploaded_file($_FILES['media']['tmp_name'], $target_file)) {
            // Enregistrez le chemin du fichier dans la base de données
            $media_path = $target_file;
        } else {
            echo "Erreur lors du téléchargement du fichier.";
            exit;
        }
    }

    $lieu = isset($_POST['lieu']) ? $conn->real_escape_string($_POST['lieu']) : NULL;
    $heure = isset($_POST['heure']) ? $conn->real_escape_string($_POST['heure']) : NULL;
    

    // Insérer le post avec ou sans média et avec ou sans dates d'événement
    $sql_insert_post = "INSERT INTO posts (username, content, is_event, date_debut, date_fin, lieu, heure, media) VALUES ('$username', '$content', '$is_event', '$date_debut', '$date_fin', '$lieu', '$heure', '$media_path')";
    if ($conn->query($sql_insert_post) === TRUE) {
        echo "Post créé avec succès.";
    } else {
        echo "Erreur lors de la création du post: " . $conn->error;
    }
}

// Si le formulaire de commentaire est soumis
if (isset($_POST['submit_comment'])) {
    $username = $_SESSION['username'];
    $id = $_POST['id'];
    $comment_content = $conn->real_escape_string($_POST['comment_content']);

    // Insérer le commentaire dans la base de données
    $sql_insert_comment = "INSERT INTO comments (id, username, content) VALUES ('$id', '$username', '$comment_content')";
    if ($conn->query($sql_insert_comment) === TRUE) {
        echo "Commentaire ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout du commentaire: " . $conn->error;
    }
}

// Si un like est soumis
if (isset($_POST['like'])) {
    $username = $_SESSION['username'];
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
    if (!empty($post_id)) {
        $sql_check_like = "SELECT * FROM post_likes WHERE post_id = '$post_id' AND username = '$username'";
        $result_check_like = $conn->query($sql_check_like);

        if ($result_check_like->num_rows == 0) {
            $sql_insert_like = "INSERT INTO post_likes (post_id, username) VALUES ('$post_id', '$username')";
            if ($conn->query($sql_insert_like) === TRUE) {
                // Mettre à jour le compteur de likes dans la table posts
                $sql_update_likes = "UPDATE posts SET like_count = like_count + 1 WHERE id = '$post_id'";
                if ($conn->query($sql_update_likes) === TRUE) {
                    echo "Post liké avec succès.";
                } else {
                    echo "Erreur lors de la mise à jour du compteur de likes: " . $conn->error;
                }
            } else {
                echo "Erreur lors du like du post: " . $conn->error;
            }
        } else {
            echo "Vous avez déjà liké ce post.";
        }
    } else {
        echo "L'ID du post n'est pas défini.";
    }
}




// Si un partage est soumis
if (isset($_POST['share'])) {
    $username = $_SESSION['username'];
    // Récupérer l'ID du post à partager à partir des données de la requête AJAX
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';

    // Vérifier si $post_id est défini
    if (!empty($post_id)) {
        $sql_insert_share = "INSERT INTO post_shares (post_id, username) VALUES ('$post_id', '$username')";
        if ($conn->query($sql_insert_share) === TRUE) {
            echo "Post partagé avec succès.";
        } else {
            echo "Erreur lors du partage du post: " . $conn->error;
        }
    } else {
        echo "L'ID du post n'est pas défini.";
    }
}


// Obtenir la date de début et de fin de la semaine actuelle
$start_week = date("Y-m-d", strtotime('monday this week'));
$end_week = date("Y-m-d", strtotime('sunday this week'));

// Sélectionner les événements de la semaine
$sql_events = "SELECT content, date_debut, date_fin, media FROM posts WHERE is_event = 1 AND date_debut >= '$start_week' AND date_fin <= '$end_week'";
$result_events = $conn->query($sql_events);

$events = [];
if ($result_events->num_rows > 0) {
    while ($row_event = $result_events->fetch_assoc()) {
        $events[] = $row_event;
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - ECE In</title>
    <link rel="stylesheet" href="accueil.css">
    <script src="accueil.js"></script>
    <!-- Liens vers les fichiers CSS de Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css"/>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>

</head>
<body>
<header>
        <div class="logo">
            <a href="accueil.php"><img src="logo.jpg" alt="Logo ECE In"></a>
        </div>
        <nav>
            <ul>
                <li><div class="ongletSelect"><a href="accueil.php">Accueil</a></div></li>
                <li><div class="onglet"><a href="reseau.php">Mon Réseau</a></div></li>
                <li><div class="onglet"><a href="vous.php">Vous</a></div></li>
                <li><div class="onglet"><a href="notifications.php">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.php">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.php">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
    <main>
        <section class="presentation">
            <h1>Bienvenue sur ECE In</h1>
            <p>ECE In est un réseau social professionnel conçu pour les membres de la communauté ECE Paris. Que vous soyez étudiant de licence, master, doctorat, apprenti en entreprise, enseignant ou employé de l'école, ECE In est là pour vous aider à prendre votre vie professionnelle au sérieux.</p>
            <p>Notre plateforme offre un espace où vous pouvez vous connecter avec d'autres professionnels, échanger des idées, rechercher des opportunités de carrière, et bien plus encore.</p>
        </section>
        <section class="event">
            <h2>Évènements de la semaine</h2>
            <p>Restez informé sur les événements importants de la semaine à l'ECE Paris.</p>
            <!-- Conteneur du carrousel -->
            <div class="slick-carousel">
                <div class="carousel-slide"><img src="uploads/image1.jpg" alt="Image 1" class="carousel-image"></div>
                <div class="carousel-slide"><img src="uploads/image2.jpg" alt="Image 2" class="carousel-image"></div>
                <div class="carousel-slide"><img src="uploads/image3.jpg" alt="Image 3" class="carousel-image"></div>
            </div>
        </section>

    <div class="main-content">
        <div class="left-content">
            <section class="create-post">
                <h2>Créer un post</h2>
                <?php
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    $sql = "SELECT photoProfil FROM utilisateur WHERE username='$username'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<a href='vous.php'><img src='".$row['photoProfil']."' alt='Photo de profil' class='profile-pic'></a>";
                    }
                }
                ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <textarea name="content" placeholder="Quoi de neuf ?" required></textarea>
                    <input type="file" name="media" accept="image/,video/">
                    <label for="lieu">Lieu :</label>
<input type="text" id="lieu" name="lieu">

<label for="heure">Heure :</label>
<input type="time" id="heure" name="heure">

                    <div class="event-container">
                        <label for="is_event">Événement :</label>
                        <input type="checkbox" id="is_event" name="is_event" onchange="toggleEventDates()">
                    </div>
                    <div id="date_fields" style="display:none;">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" id="date_debut" name="date_debut">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" id="date_fin" name="date_fin">
                    </div>
                    <button type="submit" name="submit">Publier</button>
                </form>
            </section>
            <section class="feed">
                <h2>Fil d'actualités</h2>
                <?php
$username = $_SESSION['username'];
$sql_posts = "SELECT posts.id, posts.lieu, posts.heure,posts.content, posts.created_at, posts.media, posts.like_count, utilisateur.username, utilisateur.photoProfil FROM posts JOIN utilisateur ON posts.username = utilisateur.username WHERE (utilisateur.profil_public = 1 OR utilisateur.username = '$username' OR utilisateur.username IN (SELECT friend_name FROM friends WHERE username = '$username' AND status = 'accepted') OR utilisateur.username IN (SELECT username FROM friends WHERE friend_name = '$username' AND status = 'accepted')) ORDER BY posts.created_at DESC";

  $result_posts = $conn->query($sql_posts);
                if ($result_posts->num_rows > 0) {
                    while ($row_post = $result_posts->fetch_assoc()) {
                        echo "<div class='post'>";
echo "<a href='profil.php?username=" . $row_post['username'] . "'>";
echo "<img src='".$row_post['photoProfil']."' alt='Photo de profil' class='profile-pic'>";
echo "</a>";
echo "<div class='post-content'>";
echo "<h3>".$row_post['username']."</h3>";
echo "<p>".$row_post['content']."</p>";
$file_extension = pathinfo($row_post['media'], PATHINFO_EXTENSION);
$allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$allowed_video_extensions = ['mp4', 'webm', 'ogg'];
if (in_array($file_extension, $allowed_image_extensions)) {
    echo "<img src='".$row_post['media']."' alt='Image du post' style='max-width:100%;'>";
} elseif (in_array($file_extension, $allowed_video_extensions)) {
    echo "<video controls style='max-width:100%;'>
            <source src='".$row_post['media']."' type='video/mp4'>
            Your browser does not support the video tag.
          </video>";
}
if (isset($row_post['lieu']) && !empty($row_post['lieu'])) {
    echo "<p><i>Lieu :</i> " . $row_post['lieu'] . "</p>";
}

if (isset($row_post['heure']) && !empty($row_post['heure']) && $row_post['heure'] !== '00:00:00') {
    echo "<p><i>Heure :</i> " . $row_post['heure'] . "</p>";
}

// Ajouter les boutons de like et de share
echo "<form action='' method='post'>";
$post_id = $row_post['id'];
echo "<input type='hidden' name='post_id' value='$post_id'>";
echo "<button type='submit' name='like' class='like-button'>Like</button>";
echo "<span class='like-count'>Likes: ".$row_post['like_count']."</span>";
echo "<button type='submit' name='share' class='share-button'>Partager</button>";
echo "</form>";


echo "<span class='timestamp'>".$row_post['created_at']."</span>";
echo "<form method='POST' action=''>";
echo "<textarea name='comment_content' placeholder='Ajouter un commentaire...' required></textarea>";
echo "<input type='hidden' name='id' value='".$row_post['id']."'>";
echo "<button type='submit' name='submit_comment'>Commenter</button>";
echo "</form>";


 

                        // Afficher les commentaires
                        $id = $row_post['id'];
                        $sql_comments = "SELECT * FROM comments WHERE id = '$id' ORDER BY created_at DESC";
                        $result_comments = $conn->query($sql_comments);

                        if ($result_comments->num_rows > 0) {
                            echo "<div class='comments'>";
                            while ($row_comment = $result_comments->fetch_assoc()) {
                                echo "<div class='comment'>";
                                echo "<p><strong>".$row_comment['username'].":</strong> ".$row_comment['content']."</p>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p>Aucun commentaire.</p>";
                        }
                        echo "</div>"; // Fermeture de post-content
                        echo "</div>"; // Fermeture de post
                    }
                } else {
                    echo "<p>Aucun post disponible.</p>";
                }
                ?>
            </section>
            <section class="contact">
                <h2>Nous contacter</h2>
                <p>Vous pouvez nous contacter par les moyens suivants :</p>
                <ul>
                    <li><a href="mailto:admin@ecein.com">Email: admin@ecein.com</a></li>
                    <li>Téléphone: +33 1 23 45 67 89</li>
                    <li>Adresse: 10 Rue Sextius Michel, 75015 Paris, France</li>
                </ul>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.816863599406!2d2.2914572156740104!3d48.83747107928404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67161ec115303%3A0x20b44f189d292990!2s10%20Rue%20Sextius%20Michel%2C%2075015%20Paris%2C%20France!5e0!3m2!1sen!2sus!4v1622003223999!" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </section>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="accueil.js"></script>
<?php
$conn->close();
?>
</body>
</html>