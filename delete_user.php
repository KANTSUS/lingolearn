<?php
session_start();

// Ensure the user is logged in as Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: home.php"); // Redirect non-admins
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Database connection
    $host = 'localhost';
    $db = 'lingolearn';
    $user = 'root'; 
    $password = ''; 

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to admin dashboard after deletion
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No user selected for deletion.";
}
?>
