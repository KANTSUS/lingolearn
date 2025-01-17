<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect to home page if not an admin
    header("Location: home.php");
    exit();
}

$host = 'localhost';
$db = 'lingolearn';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user_id is set in the POST request
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // SQL query to delete the user
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}

$conn->close();
?>
