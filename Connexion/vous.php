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
    $sql_update_description = "UPDATE utilisateur SET description='$descriptionU' WHERE username='$username'";
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
if (isset($_POST["generate_xml"])) {
    $username = $_SESSION['username'];
    
    // Récupérer les informations de l'utilisateur depuis la base de données
    $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
    $result_user = $conn->query($sql_user);
    $row_user = $result_user->fetch_assoc();
    
    $sql_experience = "SELECT * FROM parcours WHERE username='$username'";
    $result_experience = $conn->query($sql_experience);
    
    $sql_education = "SELECT * FROM projet WHERE username='$username'";
    $result_education = $conn->query($sql_education);

    // Commencer à construire le fichier XML
    $xml = new SimpleXMLElement('<cv/>');
    $user_xml = $xml->addChild('utilisateur');
    $user_xml->addChild('username', $row_user['username']);
    $user_xml->addChild('email', $row_user['email']);
    $user_xml->addChild('photoProfil', $row_user['photoProfil']);

    
    $experiences_xml = $user_xml->addChild('parcours');
    while ($row_experience = $result_experience->fetch_assoc()) {
        $experience_xml = $experiences_xml->addChild('experience');
        $experience_xml->addChild('titre', $row_experience['titre']);
        $experience_xml->addChild('date_debut', $row_experience['date_debut']);
        $experience_xml->addChild('date_fin', $row_experience['date_fin']);
        $experience_xml->addChild('description', $row_experience['description']);
    }

    $education_xml = $user_xml->addChild('projets');
    while ($row_education = $result_education->fetch_assoc()) {
        $education_entry_xml = $education_xml->addChild('entry');
        $education_entry_xml->addChild('titre', $row_education['titre']);
        $education_entry_xml->addChild('date_debut', $row_education['date_debut']);
        $education_entry_xml->addChild('date_fin', $row_education['date_fin']);
        $education_entry_xml->addChild('description', $row_education['description']);
    }

    // Sauvegarder le fichier XML sur le serveur
    $xml_file = "cv_xml/" . $username . "_cv.xml";
    $xml->asXML($xml_file);

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
// Si le formulaire pour modifier les paramètres de confidentialité du profil est soumis
if (isset($_POST['submit_privacy_settings'])) {
    $profil_public = isset($_POST['profil_public']) ? 1 : 0; // 1 pour profil public, 0 pour profil privé
    $username = $_SESSION['username'];
    $sql_update_privacy_settings = "UPDATE utilisateur SET profil_public='$profil_public' WHERE username='$username'";
    if ($conn->query($sql_update_privacy_settings) === TRUE) {
        echo "Paramètres de confidentialité du profil mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour des paramètres de confidentialité du profil: " . $conn->error;
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
if (isset($_POST['submit_mood'])) {
    // Vérifiez si $_SESSION['username'] est définie
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $selected_mood = $conn->real_escape_string($_POST['mood']);
        
        // Requête pour obtenir le chemin de l'image associée à l'humeur sélectionnée
        $sql_select_image = "SELECT image_path FROM mood_images WHERE mood='$selected_mood'";
        $result_image = $conn->query($sql_select_image);
        
        if ($result_image->num_rows > 0) {
            $row_image = $result_image->fetch_assoc();
            $image_path = $row_image['image_path'];
            
            // Enregistrez l'association d'humeur dans la base de données pour cet utilisateur
            $sql_update_mood = "UPDATE utilisateur SET mood_image='$image_path' WHERE username='$username'";
            if ($conn->query($sql_update_mood) === TRUE) {
                echo "Humeur mise à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de l'humeur: " . $conn->error;
            }
        } else {
            echo "Aucune image n'a été trouvée pour cette humeur.";
        }
    } else {
        echo "Erreur : utilisateur non authentifié.";
    }
}


if (isset($_POST["generate_xml"])) {
    $username = $_SESSION['username'];
    $xml_file = "cv_xml/" . $username . "_cv.xml";
    
    if (file_exists($xml_file)) {
        $xml = simplexml_load_file($xml_file);
        
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV de ' . $xml->utilisateur->username . '</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        h1, h2 { color: #333; }
        .section { margin-bottom: 20px; }
        .section h2 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        .experience, .projet { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>CV de ' . $xml->utilisateur->username . '</h1>
        <img src="../' . $row_user['photoProfil'] . '" alt="Photo de profil" class="profile-pic" width="100" height="100">
        <div class="section">
            <h2>Informations Personnelles</h2>
            <p><strong>Nom d\'utilisateur:</strong> ' . $xml->utilisateur->username . '</p>
            <p><strong>Email:</strong> ' . $xml->utilisateur->email . '</p>
            <p><strong>Description:</strong> ' . $xml->utilisateur->description . '</p>
        </div>
        <div class="section">
            <h2>Parcours</h2>';

        foreach ($xml->utilisateur->parcours->experience as $experience) {
            $html .= '<div class="experience">
                <h3>' . $experience->titre . '</h3>
                <p><strong>Date de début:</strong> ' . $experience->date_debut . '</p>
                <p><strong>Date de fin:</strong> ' . $experience->date_fin . '</p>
                <p>' . $experience->description . '</p>
            </div>';
        }

        $html .= '</div>
        <div class="section">
            <h2>Projets</h2>';

        foreach ($xml->utilisateur->projets->entry as $projet) {
            $html .= '<div class="projet">
                <h3>' . $projet->titre . '</h3>
                <p><strong>Date de début:</strong> ' . $projet->date_debut . '</p>
                <p><strong>Date de fin:</strong> ' . $projet->date_fin . '</p>
                <p>' . $projet->description . '</p>
            </div>';
        }

        $html .= '</div>
    </div>
</body>
</html>';

        $html_file = "cv_html/" . $username . "_cv.html";
        file_put_contents($html_file, $html);

        echo "Fichier HTML généré avec succès. <a href='$html_file' target='_blank'>Voir le fichier HTML</a>";
    } else {
        echo "Le fichier XML n'existe pas.";
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
            <li><div class="onglet"><a href="messagerie.php">Messagerie</a></div></li>
            <li><div class="onglet"><a href="emplois.php">Emplois</a></div></li>
        </ul>
    </nav>
</header>
<section class="user-profil-photo">
<?php
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
    $result_user = $conn->query($sql_user);
    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        echo "<h1>" . $row_user['username'] . "</h1>";
        echo '<div class="profile-container">';
        echo '<img src="' . $row_user['photoProfil'] . '" alt="Photo de profil" class="profile-pic">';
        echo '<img src="' . $row_user['mood_image'] . '" alt="Image d\'humeur" class="mood-pic">';
        echo '</div>';
        echo '<p>' . $row_user['description'] . '</p>';
        echo '<form action="" method="post">';
        echo '<select name="mood">';
        echo '<option value="Heureux">Heureux</option>';
        echo '<option value="Triste">Triste</option>';
        echo '<option value="Endormi">Endormi</option>';
        echo '<option value="Au travail">Au travail</option>';
        echo '</select>';
        echo '<input type="submit" name="submit_mood" value="Changer d\'humeur">';
        echo '</form>';

        // Formulaire pour uploader une nouvelle photo de profil
        echo '<h4>Changer de photo de profil</h4>';
        echo '<form action="" method="post" enctype="multipart/form-data">';
        echo '<input type="file" name="photo" accept="image/*"><br>';
        echo '<input type="submit" name="submit" value="Valider"><br>';
        echo '</form>';
    }
}
?>
</section>
        </section>
        <div class="container">
        <section class="left-column">

<?php
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql_user = "SELECT * FROM utilisateur WHERE username='$username'";
    $result_user = $conn->query($sql_user);
    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
// Formulaire pour ajouter ou modifier la description
echo '<div class="post">';
echo '<section class="section-description"><h2>Ajouter une description</h2>';
echo '<form action="" method="post">';
echo '<textarea name="descriptionU" placeholder="Ajouter une description..."></textarea><br>';
echo '<input type="submit" name="submit_description" value="Valider"><br>';
echo '</form>';
echo '</section>';

    }}

echo '<h2>Paramètres de confidentialité du profil</h2>';
echo '<form action="" method="post">';
echo '<input type="checkbox" name="profil_public" id="profil_public" ' . ($row_user['profil_public'] == 1 ? 'checked' : '') . '>';
 
echo '<label for="profil_public">Profil public</label><br>';
echo '<input type="submit" name="submit_privacy_settings" value="Enregistrer"><br>';
echo '</form>';
echo '</div>';
echo '<div class="post">';
echo '<h2>Vos amis</h2>';

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
echo '</div>';
echo '<div class="post">';
echo '<h2>Téléchargez votre CV</h2>';
echo'<form action="" method="post">';
    echo'<input type="submit" name="generate_xml" value="Générer le CV"><br>';
echo'</form>';





echo '</div>';
echo '</section>';

echo '<section class="right-column">';
echo '<div class="post">';
echo '<h2>Ajouter une étape de parcours</h2>';
echo '<form action="" method="post">';
    echo '<input type="text" name="titre" placeholder="Titre" required><br>';
    echo '<textarea name="description" placeholder="Description du parcours" required></textarea><br>';
    echo '<input type="date" name="date_debut" required><br>';
    echo '<input type="date" name="date_fin" required><br>';
    echo '<input type="submit" name="submit_parcours" value="Ajouter"><br>';
echo '</form>';
echo '</div>';
echo '<div class="post">';
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
echo '</div>';
$username = $_SESSION['username'];
$sql_posts = "SELECT posts.id, posts.content, posts.created_at, posts.media, utilisateur.username, utilisateur.photoProfil
            FROM posts 
            JOIN utilisateur ON posts.username = utilisateur.username 
            WHERE utilisateur.username = '$username' 
            ORDER BY posts.created_at DESC";

$result_posts = $conn->query($sql_posts);
if ($result_posts->num_rows > 0) {
while ($row_post = $result_posts->fetch_assoc()) {
    echo "<div class='post'>";
    echo "<div class='post2'>";
    echo "<a href='profil.php?username=" . $row_post['username'] . "'>";
    echo "<img src='".$row_post['photoProfil']."' alt='Photo de profil' class='profile-pic2'>";
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
    
    echo "<span class='timestamp'>".$row_post['created_at']."</span>";
    echo "<form method='POST' action=''>";
    echo "<textarea name='comment_content' placeholder='Ajouter un commentaire...' required></textarea><br>";
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
    
     // Ajout du bouton de suppression du post
     echo "<form method='POST' action=''>";
     echo "<input type='hidden' name='post_id' value='".$row_post['id']."'>";
     echo "<button type='submit' name='delete_post'>Supprimer le post</button>";
     echo "</form>";
    
    echo "</div>"; // Fermeture de post-content
    echo "</div>"; // Fermeture de post
}
} else {
echo "<p>Aucun post disponible.</p>";
}
   
echo "</div>";
echo "</div>";
?>

</section>

</body>
<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</html>

<?php
$conn->close();
?>