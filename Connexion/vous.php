<?php
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

// Vérifie si le formulaire pour changer la description a été soumis
if (isset($_POST['submit_description'])) {
    $descriptionU = $conn->real_escape_string($_POST['descriptionU']);
    $username = $_SESSION['username'];
    $sql_update_description = "UPDATE utilisateur SET descriptionU='$descriptionU' WHERE username='$username'";
    if ($conn->query($sql_update_description) === TRUE) {
        echo "Description mise à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de la description: " . $conn->error;
    }
}

// Si le formulaire pour uploader une nouvelle photo de profil est soumis
if (isset($_POST["submit"])) {
    $username = $_SESSION['username'];
    $target_dir = "photos/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $sql_update_photo = "UPDATE utilisateur SET photoProfil='$target_file' WHERE username='$username'";
            if ($conn->query($sql_update_photo) === TRUE) {
                echo "Photo de profil mise à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de la photo de profil: " . $conn->error;
            }
        } else {
            echo "Désolé, une erreur s'est produite lors de l'envoi de votre fichier.";
        }
    } else {
        echo "Le fichier n'est pas une image.";
    }
}

// Si le formulaire pour uploader un CV est soumis
if (isset($_POST["submit_cv"])) {
    $username = $_SESSION['username'];
    $target_dir = "cv/";
    $target_file = $target_dir . basename($_FILES["cv"]["name"]);
    if ($_FILES["cv"]["size"] > 0) {
        if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
            $sql_update_cv = "UPDATE utilisateur SET cv='$target_file' WHERE username='$username'";
            if ($conn->query($sql_update_cv) === TRUE) {
                echo "Le CV a été mis à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour du CV: " . $conn->error;
            }
        } else {
            echo "Désolé, une erreur s'est produite lors de l'envoi de votre fichier.";
        }
    } else {
        echo "Le fichier n'est pas valide.";
    }
}

// Si le formulaire pour supprimer le CV est soumis
if (isset($_POST['delete_cv'])) {
    $username = $_SESSION['username'];
    $sql_delete_cv = "UPDATE utilisateur SET cv=NULL WHERE username='$username'";
    if ($conn->query($sql_delete_cv) === TRUE) {
        echo "CV supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du CV: " . $conn->error;
    }
}

// Si le formulaire pour ajouter une étape de parcours est soumis
if (isset($_POST['submit_parcours'])) {
    $titre = $conn->real_escape_string($_POST['titre']);
    $description = $conn->real_escape_string($_POST['description']);
    $date_debut = $conn->real_escape_string($_POST['date_debut']);
    $date_fin = $conn->real_escape_string($_POST['date_fin']);
    $username = $_SESSION['username'];

    $sql_insert_parcours = "INSERT INTO parcours (username, titre, description, date_debut, date_fin) 
                            VALUES ('$username', '$titre', '$description', '$date_debut', '$date_fin')";
    if ($conn->query($sql_insert_parcours) === TRUE) {
        echo "Étape de parcours ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'étape de parcours: " . $conn->error;
    }
}

// Si le formulaire pour supprimer un post est soumis
if (isset($_POST['delete_post'])) {
    $post_id = $conn->real_escape_string($_POST['post_id']);
    $sql_delete_post = "DELETE FROM posts WHERE id='$post_id'";
    if ($conn->query($sql_delete_post) === TRUE) {
        echo "Post supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du post: " . $conn->error;
    }
}

// Si le formulaire pour supprimer un ami est soumis
if (isset($_POST["remove_friend"])) {
    $friend_username = $conn->real_escape_string($_POST['friend_username']);
    $username = $_SESSION['username'];
    $sql_remove_friend = "DELETE FROM friends WHERE (username='$username' AND friend_name='$friend_username') 
                          OR (username='$friend_username' AND friend_name='$username')";
    if ($conn->query($sql_remove_friend) === TRUE) {
        echo "Ami supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'ami: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="vous.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="accueil.php"><img src="logo.jpg" alt="Logo ECE In"></a>
    </div>
    <nav>
        <ul>
            <li><div class="onglet"><a href="accueil.php">Accueil</a></div></li>
            <li><div class="onglet"><a href="reseau.php">Mon Réseau</a></div></li>
            <li><div class="ongletSelect"><a href="vous.php">Vous</a></div></li>
            <li><div class="onglet"><a href="notifications.php">Notifications</a></div></li>
            <li><div class="onglet"><a href="messagerie.html">Messagerie</a></div></li>
            <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
        </ul>
    </nav>
</header>
<section class="user-profile">
    <?php
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
        $result_user = $conn->query($sql_user);
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            echo "<h1>" . $row_user['username'] . "</h1>";
            echo '<img src="' . $row_user['photoProfil'] . '" alt="Photo de profil" class="profile-pic">';
            // Formulaire pour uploader une nouvelle photo de profil
            echo '<h4>Changer de photo de profil</h4>';
            echo '<form action="" method="post" enctype="multipart/form-data">';
            echo '<input type="file" name="photo" accept="image/*"><br>';
            echo '<input type="submit" name="submit" value="Valider"><br>';
            echo '</form>';
            // Formulaire pour ajouter ou modifier la description
            echo '<section class="section-description"><h2>Ajouter une description</h2>';
            echo '<form action="" method="post">';
            echo '<textarea name="descriptionU" placeholder="Ajouter une description..."></textarea><br>';
            echo '<input type="submit" name="submit_description" value="Valider"><br>';
            echo '</form>';
        }
    }
    ?>

    <!-- Afficher les amis acceptés -->
    <h2>Vos amis</h2>
    <?php
    $sql_friends = "SELECT u.username, u.photoProfil FROM utilisateur u 
                    JOIN friends f ON u.username = f.friend_name 
                    WHERE f.username = '$username' AND f.status='accepted'
                    UNION
                    SELECT u.username, u.photoProfil FROM utilisateur u 
                    JOIN friends f ON u.username = f.username 
                    WHERE f.friend_name = '$username' AND f.status='accepted'";
    $result_friends = $conn->query($sql_friends);
    if ($result_friends->num_rows > 0) {
        while ($row_friend = $result_friends->fetch_assoc()) {
            echo '<div class="friend">';
            echo '<img src="' . $row_friend['photoProfil'] . '" alt="Photo de profil de ' . $row_friend['username'] . '" class="friend-pic">';
            echo '<p>' . $row_friend['username'] . '</p>';
            echo '<form action="" method="post" style="margin-left: 20px;">';
            echo '<input type="hidden" name="friend_username" value="' . $row_friend['username'] . '">';
            echo '<input type="submit" name="remove_friend" value="Supprimer l\'ami">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>Vous n\'avez aucun ami.</p>';
    }
    ?>
    <h2>Ajouter votre CV</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="cv" accept=".pdf,.doc,.docx"><br>
        <input type="submit" name="submit_cv" value="Uploader le CV"><br>
    </form>

    <?php
    // Afficher le CV actuel
    $sql_get_cv = "SELECT cv FROM utilisateur WHERE username='$username'";
    $result_get_cv = $conn->query($sql_get_cv);
    if ($result_get_cv->num_rows > 0) {
        $row_get_cv = $result_get_cv->fetch_assoc();
        if (!empty($row_get_cv['cv'])) {
            echo "<h2>Votre CV</h2>";
            echo '<a href="' . $row_get_cv['cv'] . '" target="_blank">Voir le CV</a>';
            // Formulaire pour supprimer le CV
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="username" value="' . $username . '">';
            echo '<button type="submit" name="delete_cv">Supprimer le CV</button>';
            echo '</form>';
        } else {
            echo "<p>Aucun CV disponible.</p>";
        }
    } else {
        echo "<p>Aucun CV disponible.</p>";
    }
    ?>

    <!-- Formulaire pour ajouter une étape de parcours -->
    <h2>Ajouter une étape de parcours</h2>
    <form action="" method="post">
        <input type="text" name="titre" placeholder="Titre" required><br>
        <textarea name="description" placeholder="Description du parcours" required></textarea><br>
        <input type="date" name="date_debut" required><br>
        <input type="date" name="date_fin" required><br>
        <input type="submit" name="submit_parcours" value="Ajouter"><br>
    </form>

    <?php
    // Récupérer et afficher la frise chronologique du parcours de l'utilisateur
    $sql_parcours = "SELECT * FROM parcours WHERE username='$username' ORDER BY date_debut ASC";
    $result_parcours = $conn->query($sql_parcours);
    if ($result_parcours->num_rows > 0) {
        echo "<h2>Parcours</h2>";
        echo '<ul class="timeline">';
        while ($row_parcours = $result_parcours->fetch_assoc()) {
            echo '<li class="timeline-item">';
            echo '<h3>' . $row_parcours['titre'] . '</h3>';
            echo '<p>' . $row_parcours['description'] . '</p>';
            echo '<span>' . $row_parcours['date_debut'] . ' - ' . $row_parcours['date_fin'] . '</span>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "<p>Aucun parcours disponible.</p>";
    }

    // Récupérer et afficher les posts de l'utilisateur
    $sql_posts = "SELECT * FROM posts WHERE username='$username' ORDER BY created_at DESC";
    $result_posts = $conn->query($sql_posts);
    if ($result_posts->num_rows > 0) {
        echo "<h2>Vos Posts</h2>";
        while ($row_post = $result_posts->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<div class='post-content'>";
            echo "<h3>" . $row_post['username'] . "</h3>";
            echo "<p>" . $row_post['content'] . "</p>";
            if (!empty($row_post['image'])) {
                echo "<img src='" . $row_post['image'] . "' alt='Image du post' style='max-width:10%;'><br>";
            }
            echo "<span class='timestamp'>" . $row_post['created_at'] . "</span>";
            // Formulaire pour supprimer le post
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="post_id" value="' . $row_post['id'] . '">';
            echo '<button type="submit" name="delete_post">Supprimer</button>';
            echo '</form>';
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucun post disponible.</p>";
    }
    ?>
</section>
</body>
</html>

<?php
$conn->close();
?>
