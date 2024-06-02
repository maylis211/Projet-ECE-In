<?php
session_start();

// Vérifier si l'utilisateur est un administrateur
if ($_SESSION['role'] != 'administrateur') {
    header("Location: accueil.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les informations de l'administrateur
$sql = "SELECT * FROM utilisateur WHERE role = 'administrateur' LIMIT 1";
$result = $conn->query($sql);

$adminInfo = null;
if ($result->num_rows > 0) {
    $adminInfo = $result->fetch_assoc();
}

// Ajouter un utilisateur
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    $sql = "INSERT INTO utilisateur (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "Nouvel utilisateur ajouté avec succès";
    } else {
        echo "Erreur: " . $conn->error;
    }
}

// Supprimer un utilisateur
if (isset($_POST['delete_user'])) {
    $username = $_POST['username'];
    
    $sql = "DELETE FROM utilisateur WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        echo "Utilisateur supprimé avec succès";
    } else {
        echo "Erreur: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Gestion des utilisateurs</h1>
    </header>
    <div class="container">
        <main>
            <div class="admin-info">
                <?php if ($adminInfo) : ?>
                    <h2>Informations de l'administrateur</h2>
                    <img src="<?php echo $adminInfo['photoProfil']; ?>" alt="Photo de profil de l'administrateur">
                    <p>Nom d'utilisateur : <?php echo $adminInfo['username']; ?></p>
                    <!-- Ajoutez d'autres informations de l'administrateur si nécessaire -->
                <?php endif; ?>
            </div>
            <div class="gestion-utilisateurs">
                <h2>Ajouter un utilisateur</h2>
                <form action="admin.php" method="POST">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" required><br><br>
                    
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    
                    <label for="email">Email ECE:</label>
                    <input type="email" id="email" name="email" required><br><br>

                    <label for="role">Rôle:</label>
                    <select id="role" name="role" required>
                        <option value="auteur">Auteur</option>
                        <option value="administrateur">Administrateur</option>
                    </select><br><br>

                    <input type="submit" name="add_user" value="Ajouter" class="btn-add">
                </form>
                <h2>Supprimer un utilisateur</h2>
                <form action="admin.php" method="POST">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" required><br><br>

                    <input type="submit" name="delete_user" value="Supprimer" class="btn-delete">
                </form>
            </div>
        </main>
    </div>
</body>
<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</html>
