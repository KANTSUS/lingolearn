<?php
require_once 'db_connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: login.php");
    exit();
}

$grade = $_GET['grade'] ?? null;

if ($grade) {
    $stmt = $pdo->prepare("
        SELECT r.student_username, q.question_text, r.student_answer, q.correct_answer, r.is_correct
        FROM pre_test_results r
        JOIN pre_test_questions q ON r.question_id = q.id
        WHERE q.grade = ?
    ");
    $stmt->execute([$grade]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Please specify a grade.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pre-Test Results</title>
</head>
<body>
    <h1>Pre-Test Results for Grade <?php echo htmlspecialchars($grade); ?></h1>
    <table border="1">
        <tr>
            <th>Student</th>
            <th>Question</th>
            <th>Student Answer</th>
            <th>Correct Answer</th>
            <th>Correct?</th>
        </tr>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?php echo htmlspecialchars($result['student_username']); ?></td>
                <td><?php echo htmlspecialchars($result['question_text']); ?></td>
                <td><?php echo htmlspecialchars($result['student_answer']); ?></td>
                <td><?php echo htmlspecialchars($result['correct_answer']); ?></td>
                <td><?php echo $result['is_correct'] ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
