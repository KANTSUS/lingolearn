<?php
$host = 'localhost'; // Your database host
$db = 'lingo_learn'; // Your database name
$user = 'root'; // Your database username
$password = ''; // Your database password (for local development, it's usually empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>