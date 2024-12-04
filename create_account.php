<?php
session_start();
include('db.php'); // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Email is already in use. Please choose a different email.";
    } else {
        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        // Get the user's ID and store it in session
        $user_id = $pdo->lastInsertId();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username; // Store the username in session

        // Redirect to the homepage
        header("Location: home.php");
        exit;
    }
}
?>