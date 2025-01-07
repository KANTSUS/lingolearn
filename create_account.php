<?php
session_start();
include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Email is already in use. Please choose a different email.";
    } else {
       
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        
        $user_id = $pdo->lastInsertId();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username; 

        
        header("Location: home.php");
        exit;
    }
}
?>