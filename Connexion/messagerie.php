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
    <link rel="stylesheet" href="style.css">
    <style>
        * { margin: 0; padding: 0; }
        body { margin: 20px auto; font-family: "Lato"; font-weight: 300; }
        form { padding: 15px 25px; display: flex; gap: 10px; justify-content: center; }
        form label { font-size: 1.5rem; font-weight: bold; }
        input, textarea { font-family: "Lato"; }
        a { color: #0000ff; text-decoration: none; }
        .logo img {
    height: 50px; /* Ajustez la taille du logo selon vos besoins */
}
        a:hover { text-decoration: underline; }
        #wrapper, #loginform { margin: 0 auto; padding-bottom: 25px; background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);; width: 600px; max-width: 100%; border: 2px solid #212121; border-radius: 4px; }
        #loginform { padding-top: 18px; text-align: center; }
        #loginform p { padding: 15px 25px; font-size: 1.4rem; font-weight: bold; }
        #chatbox { text-align: left; margin: 0 auto; margin-bottom: 25px; padding: 10px; background: white; height: 400px; width: 530px; border: 1px solid #a7a7a7; overflow: auto; border-radius: 4px; border-bottom: 4px solid #a7a7a7; }
        #usermsg { flex: 1; border-radius: 4px; border: 1px solid #ff9800;background-color:#eee; }
        #name { border-radius: 4px; border: 1px solid #ff9800; padding: 2px 8px; }
        #submitmsg, #enter { background: #ff9800; border: 2px solid #e65100; color: white; padding: 4px 10px; font-weight: bold; border-radius: 4px; }
        .error { color: #ff0000; }
        #menu { padding: 15px 25px; display: flex; }
        #menu p.welcome { flex: 1; }
        a#exit { color: white; background: #c62828; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .msgln { margin: 0 0 5px 0; }
        .msgln span.left-info { color: orangered; }
        .msgln span.chat-time { color: #666; font-size: 60%; vertical-align: super; }
        .msgln b.user-name, .msgln b.user-name-left { font-weight: bold; background: #546e7a; color: white; padding: 2px 4px; font-size: 90%; border-radius: 4px; margin: 0 5px 0 0; }
        .msgln b.user-name-left { background: orangered; }
                .onglet {
    padding: 8px 12px;
    margin: 0 5px;
    border-radius: 5px;
    transition: background-color 0.3s, border-bottom 0.3s;
}
        header {
    background: linear-gradient(90deg, rgba(7,109,121,1) 0%, rgba(9,152,169,1) 98%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    margin-bottom: 30px;
}
    .chat-bubble {
        padding: 10px;
        border-radius: 10px;
        max-width: 70%;
        display: inline-block;
        margin-bottom: 10px;
    }

    .user-message {
        background-color: lightgray; /* Couleur verte pour les messages de l'utilisateur */
        color: #006600;
        margin-left: auto;
        text-align: right;
    }

    .friend-message {
        background-color: darkblue; /* Couleur grise pour les messages des amis */
        color: white;
        margin-right: auto;
        text-align: left;
    }

    .chat-time {
        font-size: 1.0em;
        color: #888;
        margin-top: 5px;
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
.onglet:hover {
    box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -webkit-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -moz-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;}

.ongletSelect {
    font-weight: bold; /* Met en gras le texte de l'onglet */
    color: #088897; /* Change la couleur du texte de l'onglet */
    border-bottom: 2px solid #088897; /* Ajoute une bordure en bas de l'onglet */
    background-color: #088897; /* Ajoute un arrière-plan gris clair */
    border-radius: 5px; /* Arrondit les coins de l'onglet */
    padding: 8px 12px; /* Ajoute de l'espace à l'intérieur de l'onglet */
    margin: 0 5px; /* Ajoute un espace entre les onglets */
    box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -webkit-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    -moz-box-shadow: 12px 12px 72px -23px rgba(41,49,56,0.9) inset;
    transition: background-color 0.3s; /* Ajoute une transition fluide lors du survol */
    
}
p.welcome {
    font-size: 1.2rem; /* Vous pouvez augmenter cette valeur pour agrandir le texte */
}

p.welcome b {
    font-size: 1.5rem; /* Vous pouvez augmenter cette valeur pour agrandir le nom d'utilisateur */
}
#chatbox {
    margin-bottom: 15px; /* Vous pouvez réduire cette valeur pour diminuer l'espace */
}
label[for="friend_name"] {
    font-size: 0.9rem; /* Vous pouvez diminuer cette valeur pour réduire le texte */
}

select#friend_name {
    font-size: 0.9rem; /* Vous pouvez diminuer cette valeur pour réduire le texte */
    padding: 2px; /* Vous pouvez réduire le padding pour diminuer la taille du champ */
}

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
