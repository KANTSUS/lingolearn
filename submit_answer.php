<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = $_POST['question_id'];
    $student_answer = $_POST['student_answer'];
    $student_username = $_SESSION['username'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lingolearn";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO student_answers (question_id, student_answer, student_username) VALUES ('$question_id', '$student_answer', '$student_username')";
    if ($conn->query($sql) === TRUE) {
        echo "Your answer has been submitted!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Answer</title>
</head>
<body>
    <h1>Submit Your Answer</h1>
    <form action="" method="post">
        <label for="question">Question:</label><br>
        <textarea name="student_answer" id="student_answer" required></textarea><br><br>

        <input type="submit" value="Submit Answer">
    </form>
</body>
</html>