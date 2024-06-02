<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notifications.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="notifications.js"></script>

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
                <li><div class="ongletSelect"><a href="notifications.php">Notifications</a></div></li>
                <li><div class="onglet"><a href="messagerie.php">Messagerie</a></div></li>
                <li><div class="onglet"><a href="emplois.php">Emplois</a></div></li>
            </ul>
        </nav>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </header>
 
<div class="container text-center">
    <div class="button-container sticky-top">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" onclick="showEvents()" class="onlget">Evenements</button>
            <button type="button" onclick="showFriendPosts()" class="onglet">Posts des Amis</button>
            <button type="button" onclick="showOffresEmplois()" class="onglet">Offres     d'Emplois</button>
        </div>
    </div>
    <div class="feed-container">
        <div id="eventsSection" class="section">
            <h2 class="section-title">Événements</h2>
            <ul class="feed">
                
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "projet";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Échec de la connexion à la base de données: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM `evenements` ORDER BY Date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($row["Utilisateur"] == "ECE" || $row["Utilisateur"] == "Omnes Education") {
                        echo "<div class='feed-item'>";
                        echo "<div class='content'>";
                        echo "<div class='info-container'>";
                        echo "<h2 class='creator'>" . $row["Utilisateur"] . "</h2>"; // Utilisateur créateur à gauche
                        echo "<span class='date'>" . $row["Date"] . "</span>"; // Date à droite
                        echo "</div>";
                        echo "<h3>" . $row["NomEvent"] . "</h3>";
                        echo "<p>" . $row["Description"] . "</p>";
                        echo "<div class='image'><img src='" . $row["Image"] . "' alt='Image de l'événement'></div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";                
                    }
                }

                $conn->close();
                ?>
            </ul>
        </div>
        <div id="friendPostsSection" class="section" style="display: none;">
            <h2 class="section-title">Posts des Amis</h2>
            <ul class="feed">

                 <?php



                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "projet";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Échec de la connexion à la base de données: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM parcours ORDER BY date_debut DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='feed-item'>";
                        echo "<div class='content'>";
                        echo "<h2 class='creator'>" . $row["username"] . "</h2>"; // Nom de l'utilisateur
                        echo "<p><strong>Titre:</strong> " . $row["titre"] . "</p>"; // Titre du parcours
                        echo "<p><strong>Description:</strong> " . $row["description"] . "</p>"; // Description du parcours
                        echo "<p><strong>Date de début:</strong> " . $row["date_debut"] . "</p>"; // Date de début du parcours
                        echo "<p><strong>Date de fin:</strong> " . $row["date_fin"] . "</p>"; // Date de fin du parcours
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "Aucun résultat trouvé.";
                }

            $conn->close();
            ?>
            </ul>   
        </div>
        <div id="offresEmploisSection" class="section" style="display: none;">
    <h2 class="section-title">Offres d'Emplois</h2>
    <ul class="feed">
        <?php
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "projet";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Échec de la connexion à la base de données: " . $conn->connect_error);
        }

        // Requête pour sélectionner les offres d'emplois
        $sql = "SELECT * FROM offres ORDER BY date_publication DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='feed-item'>";
                echo "<div class='content'>";
                echo "<h2 class='creator'>" . $row["employeur"] . "</h2>"; // Employeur
                echo "<h3>" . $row["titre"] . "</h3>"; // Titre de l'offre
                echo "<p><strong>Description:</strong> " . $row["description"] . "</p>"; // Description
                echo "<p><strong>Type:</strong> " . $row["type"] . "</p>"; // Type de contrat
                echo "<p><strong>Localisation:</strong> " . $row["localisation"] . "</p>"; // Localisation
                echo "<p><strong>Date de publication:</strong> " . $row["date_publication"] . "</p>"; // Date de publication
                echo "<p><strong>Rémunération:</strong> " . $row["remuneration"] . "</p>"; // Rémunération
                echo "<p><strong>Contact:</strong> " . $row["contact_email"] . "</p>"; // Contact
                echo "</div>";
                echo "</div>";
                echo "<hr>";
            }
        } else {
            echo "Aucune offre d'emploi trouvée.";
        }

        $conn->close();
        ?>
    </ul>
</div>
    </div>
</div>

<footer>
    <p>&copy; 2024 ECE In. Tous droits réservés.</p>
</footer>
</body>
</html> 
