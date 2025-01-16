<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$student_username = $_SESSION['username'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    foreach ($_POST as $questionId => $answer) {
        if (strpos($questionId, 'question_') !== false) {
            $questionId = str_replace('question_', '', $questionId);
            $stmt = $conn->prepare("INSERT INTO student_answers (student_username, question_id, answer) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $student_username, $questionId, $answer);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<p>Your answers have been submitted successfully!</p>";
}
?>