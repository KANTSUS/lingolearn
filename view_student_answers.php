<?php
require_once 'db_connection.php'; // Adjust the path to your connection file
session_start();

// Check if the user is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: login.php");
    exit();
}

// Fetch all student answers for the pre-test
$stmt = $pdo->prepare("
    SELECT ps.student_username, ps.question_id, ps.student_answer, ps.is_correct, ps.grade, pq.question_text 
    FROM pre_test_results ps
    JOIN pre_test_questions pq ON ps.question_id = pq.id
");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Answers</title>
    <style>
        /* Add styles for better visualization */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .student-answer {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .question-text {
            font-weight: bold;
        }
        .answer {
            margin-top: 5px;
        }
        .correct {
            color: green;
        }
        .incorrect {
            color: red;
        }
        .grade {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>View Student Answers</h1>

    <?php if ($results): ?>
        <?php foreach ($results as $result): ?>
            <div class="student-answer">
                <p class="question-text"><?php echo htmlspecialchars($result['question_text']); ?></p>
                <p class="answer">Student's Answer: <?php echo htmlspecialchars($result['student_answer']); ?></p>
                <p class="answer <?php echo $result['is_correct'] ? 'correct' : 'incorrect'; ?>">
                    <?php echo $result['is_correct'] ? 'Correct' : 'Incorrect'; ?>
                </p>
                <p class="grade">Grade: <?php echo round($result['grade'], 2); ?>%</p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No student answers available.</p>
    <?php endif; ?>
    
</body>
</html>
