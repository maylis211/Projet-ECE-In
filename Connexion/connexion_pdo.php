<?php
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

// Vous pouvez maintenant utiliser $pdo pour exécuter des requêtes SQL