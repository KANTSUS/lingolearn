
<?php
require_once 'db_connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade = $_POST['grade'];
    $question_text = $_POST['question_text'];
    $correct_answer = $_POST['correct_answer'];

    $stmt = $pdo->prepare("INSERT INTO pre_test_questions (grade, question_text, correct_answer) VALUES (?, ?, ?)");
    $stmt->execute([$grade, $question_text, $correct_answer]);

    echo "Pre-test question added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Pre-Test</title>
</head>
<body>
    <h1>Create Pre-Test Questions</h1>
    <form method="POST">
        <label for="grade">Grade:</label>
        <input type="text" id="grade" name="grade" required>
        <br><br>
        <label for="question_text">Question:</label>
        <textarea id="question_text" name="question_text" required></textarea>
        <br><br>
        <label for="correct_answer">Correct Answer:</label>
        <input type="text" id="correct_answer" name="correct_answer" required>
        <br><br>
        <button type="submit">Add Question</button>
    </form>
</body>
</html>
