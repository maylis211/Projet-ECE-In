<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le formulaire de connexion a été soumis
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupération du rôle

    $sql = "SELECT * FROM utilisateur WHERE username='$username' AND role='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Stockage du rôle dans la session
            if ($role == 'administrateur') {
                header("Location: admin.php");
            } else {
                header("Location: accueil.php");
            }
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect";
        }
    } else {
        echo "Utilisateur non trouvé";
    }
}

// Vérifier si le formulaire d'inscription a été soumis
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupération du rôle
    
    $sql = "INSERT INTO utilisateur (username, password, role, photoProfil) VALUES ('$username', '$password', '$role', 'profil.png')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; // Stockage du rôle dans la session
        if ($role == 'administrateur') {
            header("Location: admin.php");
        } else {
            header("Location: accueil.php");
        }
        exit();
    } else {
        echo "Erreur lors de l'inscription: " . $conn->error;
    }
}

$conn->close();
?>
