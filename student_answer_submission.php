<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Student') {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'lingolearn';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = $_POST['answer'];
    $student_id = $_SESSION['user_id']; // Assuming student ID is stored in session
    $lesson_id = $_POST['lesson_id']; // The ID of the lesson being answered

    // Save the answer to the database
    $sql = "INSERT INTO student_answers (student_id, lesson_id, answer) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $student_id, $lesson_id, $answer);
    $stmt->execute();

    echo "<p>Your answer has been submitted!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Answer</title>
</head>
<body>
    <div class="answer-form">
        <h1>Submit Your Answer</h1>
        <form method="POST">
            <label for="lesson_id">Lesson:</label>
            <input type="text" name="lesson_id" value="1" readonly><br> <!-- Just an example, should be dynamically populated -->
            
            <label for="answer">Your Answer:</label><br>
            <textarea name="answer" required></textarea><br>

            <button type="submit">Submit Answer</button>
        </form>

        <button onclick="window.location.href='home.php'">Back to Home</button>
    </div>
</body>
</html>
