<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Connexion à la base de données
require_once 'connexion_pdo.php';

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