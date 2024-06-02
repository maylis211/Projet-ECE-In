<?php
session_start();

// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'projet';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// Création de la connexion à la base de données
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Vérification si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $_SESSION['username'] = $username;
}


// Vérification si l'utilisateur est connecté
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Récupération des amis de l'utilisateur
    $stmt = $pdo->prepare("SELECT friend_name FROM friends WHERE username = ? AND status = 'accepted'
                            UNION
                            SELECT username FROM friends WHERE friend_name = ? AND status = 'accepted'");

    // Exécution de la requête avec le nom d'utilisateur
    $stmt->execute([$username, $username]);
    $friends = $stmt->fetchAll();
} else {
    $username = null;
    $friends = [];
}
// Vérification si le formulaire de création de groupe a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_group'])) {
    // Récupération des participants sélectionnés
    $participants = $_POST['participants'];

    // Création d'un ID unique pour le groupe
    $group_id = uniqid('group_');

    // Insertion des participants dans la table des groupes
    $stmt = $pdo->prepare("INSERT INTO group_chats (group_id, participant_username) VALUES (?, ?)");
    foreach ($participants as $participant_username) {
        $stmt->execute([$group_id, $participant_username]);
    }

    // Redirection vers la page de chat de groupe
    header("Location: group_chat.php?group_id=" . $group_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <link rel="stylesheet" href="style.css">
<style>
    /* ... (CSS reset et autres styles globaux ici) ... */

    header {
        background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        margin-bottom: 30px;
    }

    .logo img {
        height: 50px; /* Ajustez la taille du logo selon vos besoins */
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: flex-end;
    }

    nav ul li {
        margin: 0 15px;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
    }

    .onglet {
        padding: 8px 12px;
        margin: 0 5px;
        border-radius: 5px;
        transition: background-color 0.3s, border-bottom 0.3s;
    }

    .onglet:hover {
        box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    }

    .ongletSelect {
        font-weight: bold; /* Met en gras le texte de l'onglet */
        color: #088897; /* Change la couleur du texte de l'onglet */
        border-bottom: 2px solid #088897; /* Ajoute une bordure en bas de l'onglet */
        background-color: #088897; /* Ajoute un arrière-plan gris clair */
        border-radius: 5px; /* Arrondit les coins de l'onglet */
        padding: 8px 12px; /* Ajoute de l'espace à l'intérieur de l'onglet */
        margin: 0 5px; /* Ajoute un espace entre les onglets */
        box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
        transition: background-color 0.3s; /* Ajoute une transition fluide lors du survol */
    }
    nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: flex-end;
}

nav ul li {
    margin: 0 15px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
}

    /* ... (Autres styles spécifiques ici) ... */
</style>
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
                <li><div class="onglet"><a href="vous.php">Vous</a></div></li>
                <li><div class="onglet"><a href="notifications.php">Notifications</a></div></li>
                <li><div class="ongletSelect"><a href="messagerie.html">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.html">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
    
   <?php if (!$username): ?>
        <!-- ... (Login form here) ... -->
    <?php else: ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Bienvenue, <b><?php echo htmlspecialchars($username); ?></b></p>
                <form action="" method="post">
                    <input type="submit" name="logout" value="Déconnexion">
                </form>
            </div>
            <div class="friend-list">
                <h2>Vos amis</h2>
                <?php foreach ($friends as $friend): ?>
                    <div class="friend-item">
                        <!-- Lien vers la page de chat individuelle -->
                        <a href="chat.php?friend=<?php echo urlencode($friend['friend_name']); ?>">
                            <?php echo htmlspecialchars($friend['friend_name']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Formulaire pour créer un groupe de discussion -->
            <div class="create-group">
                <h2>Créer un groupe</h2>
                <form action="" method="post">
                    <?php foreach ($friends as $friend): ?>
                        <div class="friend-item">
                            <input type="checkbox" name="participants[]" value="<?php echo $friend['friend_name']; ?>">
                            <?php echo htmlspecialchars($friend['friend_name']); ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="submit" name="create_group" value="Créer un groupe">
                </form>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>