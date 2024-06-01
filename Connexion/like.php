<?php
session_start();
if(isset($_SESSION['username']) && isset($_POST['post_id'])) {
    $username = $_SESSION['username'];
    $post_id = $_POST['post_id'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier si l'utilisateur a déjà aimé ce post
    $sql_check_like = "SELECT * FROM post_likes WHERE post_id = '$post_id' AND username = '$username'";
    $result_check_like = $conn->query($sql_check_like);

    if ($result_check_like->num_rows == 0) {
        // Ajouter le like dans la base de données
        $sql_insert_like = "INSERT INTO post_likes (post_id, username) VALUES ('$post_id', '$username')";
        if ($conn->query($sql_insert_like) === TRUE) {
            // Mettre à jour le compteur de likes dans la table posts
            $sql_update_likes = "UPDATE posts SET like_count = like_count + 1 WHERE id = '$post_id'";
            if ($conn->query($sql_update_likes) === TRUE) {
                // Renvoyer le nouveau nombre de likes
                $sql_get_likes = "SELECT like_count FROM posts WHERE id = '$post_id'";
                $result_get_likes = $conn->query($sql_get_likes);
                $row = $result_get_likes->fetch_assoc();
                echo $row['like_count'];
            } else {
                echo "Erreur lors de la mise à jour du compteur de likes";
            }
        } else {
            echo "Erreur lors du like du post";
        }
    } else {
        echo "Vous avez déjà liké ce post.";
    }

    $conn->close();
} else {
    echo "Erreur : utilisateur non connecté ou ID de post non défini";
}
?>
