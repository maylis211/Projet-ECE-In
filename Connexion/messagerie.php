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

// Vérifiez si l'utilisateur a renseigné son nom
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
}

// Déconnexion de l'utilisateur
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: messagerie.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    // Afficher le formulaire de connexion si l'utilisateur n'est pas connecté
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <title>Connexion</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <div id="loginform">
        <h2>Veuillez saisir votre nom pour continuer</h2>
        <form action="messagerie.php" method="post">
            <label for="username">Nom d\'utilisateur:</label>
            <input type="text" name="username" id="username" required />
            <input type="submit" value="Soumettre" />
        </form>
    </div>
    </body>
    </html>
    ';
    exit();
}

$username = $_SESSION['username'];

// Récupérer l'historique des discussions
$query = "SELECT DISTINCT friend_name FROM comments WHERE username='$username' UNION SELECT DISTINCT username FROM comments WHERE friend_name='$username'";
$result = $conn->query($query);

// Récupérer la liste des amis
$friends_query = "SELECT friend_name FROM friends WHERE username='$username' AND status='accepted'";
$friends_result = $conn->query($friends_query);

// Vérifiez si une nouvelle discussion est demandée
if (isset($_POST['friend'])) {
    $friend = $_POST['friend'];
    header("Location: messagerie.php?friend=$friend");
    exit();
}

// Vérifiez si une discussion existante est demandée
if (isset($_GET['friend'])) {
    $friend = $_GET['friend'];
    $chat_mode = true;
} else {
    $chat_mode = false;
}

// Script de publication des messages
if (isset($_POST['usermsg']) && isset($_POST['friend'])) {
    $text = $_POST['usermsg'];
    $friend = $_POST['friend'];
    $query = "INSERT INTO comments (username, friend_name, content) VALUES ('$username', '$friend', '$text')";
    if ($conn->query($query) === TRUE) {
        echo "Message envoyé avec succès";
    } else {
        echo "Erreur: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Messagerie</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="wrapper">
    <?php if (!$chat_mode) { ?>
        <h2>Bienvenue, <?php echo $username; ?></h2>
        <h2>Historique des Discussions</h2>
        <div id="chat-history">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p><a href='messagerie.php?friend=" . $row['friend_name'] . "'>" . $row['friend_name'] . "</a></p>";
                }
            } else {
                echo "<p>Aucune discussion trouvée.</p>";
            }
            ?>
        </div>
        <h2>Commencer une Nouvelle Discussion</h2>
        <form action="messagerie.php" method="post">
            <label for="friend">Choisir un ami:</label>
            <select name="friend" id="friend">
                <?php
                if ($friends_result->num_rows > 0) {
                    while ($friend = $friends_result->fetch_assoc()) {
                        echo "<option value='" . $friend['friend_name'] . "'>" . $friend['friend_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Aucun ami trouvé</option>";
                }
                ?>
            </select>
            <input type="submit" value="Commencer le Chat">
        </form>
        <form action="messagerie.php" method="get">
            <input type="hidden" name="logout" value="true">
            <input type="submit" value="Changer d'utilisateur">
        </form>
    <?php } else { ?>
        <div id="menu">
            <p class="welcome">Chat avec <b><?php echo $friend; ?></b></p>
            <p class="logout"><a id="exit" href="messagerie.php">Retour à l'historique</a></p>
        </div>
        <div id="chatbox">
            <?php
            $query = "SELECT * FROM comments WHERE (username='$username' AND friend_name='$friend') OR (username='$friend' AND friend_name='$username') ORDER BY created_at";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='msgln'><span class='chat-time'>" . date("g:i A", strtotime($row['created_at'])) . "</span> <b class='user-name'>" . $row['username'] . "</b>: " . stripslashes(htmlspecialchars($row['content'])) . "<br></div>";
                }
            }
            ?>
        </div>
        <form name="message" action="messagerie.php?friend=<?php echo $friend; ?>" method="post">
            <input name="usermsg" type="text" id="usermsg" />
            <input name="submitmsg" type="submit" id="submitmsg" value="Envoyer" />
            <input type="hidden" name="friend" value="<?php echo $friend; ?>">
        </form>
    <?php } ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#submitmsg").click(function () {
        var clientmsg = $("#usermsg").val();
        var friend = $("input[name='friend']").val();
        $.post("messagerie.php?friend=<?php echo $friend; ?>", { usermsg: clientmsg, friend: friend }, function() {
            loadLog();
        });
        $("#usermsg").val("");
        return false;
    });

    function loadLog() {
        $.ajax({
            url: "load_log.php?friend=<?php echo $friend; ?>",
            cache: false,
            success: function (html) {
                $("#chatbox").html(html);
                var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
            }
        });
    }

    setInterval(loadLog, 2500);
    loadLog();
});
</script>
</body>
</html>
