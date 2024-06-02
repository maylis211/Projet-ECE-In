<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emplois</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="emplois.css">
    <script src="emplois.js"></script>

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
                <li><div class="onglet"><a href="messagerie.php">Messagerie</a></div></li>
                <li><div class="ongletSelect"><a href="emplois.php">Emplois</a></div></li>
            </ul>
        </nav>
    </header>

    <div class="container text-center">
        <div class="button-container sticky-top">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" onclick="showStage()" class="onglet" data-target="stageSection">Stage</button>
                <button type="button" onclick="showApprentissage()" class="onglet" data-target="apprentissageSection">Apprentissage</button>
                <button type="button" onclick="showCDD()" class="onglet" data-target="cddSection">CDD</button>
                <button type="button" onclick="showCDI()" class="onglet" data-target="cdiSection">CDI</button>
            </div>
        </div>
        <div class="feed-container">
            <div id="stageSection" class="section">
                <h2 class="section-title">Stages</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Stage'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre de stage disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="apprentissageSection" class="section" style="display: none;">
                <h2 class="section-title">Apprentissages</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Apprentissage'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Apprentissage' disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="cddSection" class="section" style="display: none;">
                <h2 class="section-title">CDD</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Temporaire'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Temporaire' disponible.</li>";
                    }

                    // Fermeture de la connexion
                    $conn->close();
                    ?>
                </ul>
            </div>
            <div id="cdiSection" class="section" style="display: none;">
                <h2 class="section-title">CDI</h2>
                <ul class="feed">
                    <?php
                    // Connexion à la base de données
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    // Création de la connexion
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Vérification de la connexion
                    if ($conn->connect_error) {
                        die("Échec de la connexion à la base de données: " . $conn->connect_error);
                    }

                    // Récupération des offres de stage
                    $query = "SELECT * FROM offres WHERE type = 'Permanent'";
                    $result = $conn->query($query);

                    // Affichage des offres de stage
                    if ($result->num_rows > 0) {
                        while ($job = $result->fetch_assoc()) {
                            echo "<li class='feed-item'>";
                            echo "<h3>" . htmlspecialchars($job['titre']) . "</h3>";
                            echo "<p>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
                            echo "<p><strong>Type:</strong> " . htmlspecialchars($job['type']) . "</p>";
                            echo "<p><strong>Employeur:</strong> " . htmlspecialchars($job['employeur']) . "</p>";
                            echo "<p><strong>Localisation:</strong> " . htmlspecialchars($job['localisation']) . "</p>";
                            echo "<p><strong>Date de publication:</strong> " . htmlspecialchars($job['date_publication']) . "</p>";
                            echo "<p><strong>Rémunération:</strong> " . htmlspecialchars($job['remuneration']) . "</p>";
                            echo "<p><strong>Contact:</strong> " . htmlspecialchars($job['contact_email']) . "</p>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>Aucune offre d'Permanent' disponible.</li>";
                    }

                    // Fermeture de la connexion
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
