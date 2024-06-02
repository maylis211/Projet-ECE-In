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

// Vérification si le formulaire de message a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $username = $_SESSION['username'];
    $friend_name = $_POST['friend_name'];
    $content = $_POST['content'];

    // Préparation de la requête SQL pour insérer un commentaire
    $stmt = $pdo->prepare("INSERT INTO messages(username, friend_name, content) VALUES (:username, :friend_name, :content)");

    // Liaison des paramètres à la requête
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':friend_name', $friend_name);
    $stmt->bindParam(':content', $content);

    // Exécution de la requête
    $stmt->execute();
}

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Récupération des amis de l'utilisateur
    $stmt = $pdo->prepare("SELECT friend_name FROM friends WHERE username = :username AND status = 'accepted'");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $friends = $stmt->fetchAll();

    // Récupération des discussions précédentes
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE username = :username OR friend_name = :friend_name ORDER BY created_at ASC");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':friend_name', $username);
    $stmt->execute();
    $messages = $stmt->fetchAll();

    // Organiser les messages par utilisateur
    $conversations = [];
    foreach ($messages as $comment) {
        $contact = $comment['username'] === $username ? $comment['friend_name'] : $comment['username'];
        if (!isset($conversations[$contact])) {
            $conversations[$contact] = [];
        }
        $conversations[$contact][] = $comment;
    }
} else {
    $username = null;
    $friends = [];
    $conversations = [];
}

// Vérification si l'utilisateur a cliqué sur le bouton de déconnexion
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Application</title>
    <link rel="stylesheet" href="Messagerie.css">

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
                <li><div class="ongletSelect"><a href="messagerie.php">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.php">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
 
    <?php if (!$username): ?>
        <div id="loginform">
            <p>Veuillez saisir votre nom pour continuer !</p>
            <form action="" method="post">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
                <input type="submit" name="login" id="enter" value="Se connecter">
            </form>
        </div>
    <?php else: ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Bienvenue, <b><?php echo htmlspecialchars($username); ?></b></p>
                <p class="logout">
                    <form action="" method="post">
                        <input type="submit" name="logout" id="exit" value="Changer d'utilisateur">
                    </form>
                </p>
            </div>
                <div id="chatbox">
                    <?php foreach ($conversations as $contact => $messages): ?>
                        <h3>Conversation entre vous (<?php echo htmlspecialchars($username); ?>) et <?php echo htmlspecialchars($contact); ?>:</h3>
                        <?php foreach ($messages as $comment): ?>
                            <?php $isUserMessage = ($comment['username'] === $username); ?>
                            <div class="chat-message">
                                <span class="chat-time"><?php echo date("g:i A", strtotime($comment['created_at'])); ?></span>
                                <span class="chat-sender"><?php echo htmlspecialchars($isUserMessage ? $username : $contact); ?>:</span>
                                <div class="chat-bubble <?php echo $isUserMessage ? 'user-message' : 'friend-message'; ?>">
                                    <?php echo htmlspecialchars($comment['content']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <form name="message" action="" method="post">
                <label for="friend_name">Nom de l'ami:</label>
                <select id="friend_name" name="friend_name" required>
                    <?php foreach ($friends as $friend): ?>
                        <option value="<?php echo htmlspecialchars($friend['friend_name']); ?>"><?php echo htmlspecialchars($friend['friend_name']); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <label for="content">Message:</label>
                <textarea id="content" name="content" required></textarea><br><br>
                <input type="submit" name="message" id="submitmsg" value="Envoyer">
            </form>
        </div>
    <?php endif; ?>
</body>
</html> 
