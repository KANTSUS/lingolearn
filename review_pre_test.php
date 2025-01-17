<?php
require_once 'db_connection.php'; // Adjust the path to your connection file
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: login.php");
    exit();
}

// Fetch all results for review
$stmt = $pdo->prepare("
    SELECT r.student_username, q.question_text, r.student_answer, q.correct_answer, r.is_correct 
    FROM pre_test_results r
    JOIN pre_test_questions q ON r.question_id = q.id
");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Pre-Test Results</title>
</head>
<body>
    <h1>Pre-Test Results</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Student Username</th>
                <th>Question</th>
                <th>Student Answer</th>
                <th>Correct Answer</th>
                <th>Is Correct</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['student_username']); ?></td>
                    <td><?php echo htmlspecialchars($result['question_text']); ?></td>
                    <td><?php echo htmlspecialchars($result['student_answer']); ?></td>
                    <td><?php echo htmlspecialchars($result['correct_answer']); ?></td>
                    <td><?php echo $result['is_correct'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button onclick="window.location.href = 'home.php';">Go Home</button>
</body>
</html>
