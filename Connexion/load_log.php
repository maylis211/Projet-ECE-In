<?php
session_start();
include('db_connection.php'); // Inclure le fichier de connexion à la base de données

if (isset($_SESSION['username']) && isset($_GET['friend'])) {
    $username = $_SESSION['username'];
    $friend = $_GET['friend'];

    $query = "SELECT * FROM comments WHERE (username='$username' AND friend_name='$friend') OR (username='$friend' AND friend_name='$username') ORDER BY created_at";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='msgln'><span class='chat-time'>" . date("g:i A", strtotime($row['created_at'])) . "</span> <b class='user-name'>" . $row['username'] . "</b>: " . stripslashes(htmlspecialchars($row['content'])) . "<br></div>";
        }
    }
}
?>