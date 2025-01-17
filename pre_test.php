<?php
require_once 'db_connection.php'; 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch the assigned pre-test questions for the student
$stmt = $pdo->prepare("
    SELECT p.*
    FROM pre_test_questions p
    JOIN pre_test_assignments a ON p.id = a.pre_test_id
    WHERE a.student_username = ?
");
$stmt->execute([$username]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission (answers)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    $correct_answers = 0;
    $total_questions = count($questions);

    // Loop through the answers submitted by the student
    foreach ($_POST['answers'] as $question_id => $student_answer) {
        // Fetch the correct answer for the question
        $stmt = $pdo->prepare("SELECT correct_answer FROM pre_test_questions WHERE id = ?");
        $stmt->execute([$question_id]);
        $correct_answer = $stmt->fetchColumn();

        // Check if the student's answer matches the correct answer
        $is_correct = (strtoupper($student_answer) === strtoupper($correct_answer));
        if ($is_correct) {
            $correct_answers++;
        }

        // Save the student's answer and whether it was correct
        $stmt = $pdo->prepare("
            INSERT INTO pre_test_results (student_username, question_id, student_answer, is_correct) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$username, $question_id, strtoupper($student_answer), $is_correct]);
    }

    // Calculate the grade based on correct answers
    $grade = ($correct_answers / $total_questions) * 100;

    // Insert or update the student's grade in the results table
    $stmt = $pdo->prepare("
        INSERT INTO pre_test_results (student_username, grade)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE grade = VALUES(grade)
    ");
    $stmt->execute([$username, $grade]);

    echo "<div class='success'>Pre-test submitted successfully. Your score: " . round($grade, 2) . "%</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background: #45a049;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Pre-Test</h1>
    <form method="POST">
        <?php foreach ($questions as $question): ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($question['question_text']); ?></legend>
                <label>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="A"> <?php echo htmlspecialchars($question['option_a']); ?>
                </label><br>
                <label>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="B"> <?php echo htmlspecialchars($question['option_b']); ?>
                </label><br>
                <label>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="C"> <?php echo htmlspecialchars($question['option_c']); ?>
                </label><br>
                <label>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="D"> <?php echo htmlspecialchars($question['option_d']); ?>
                </label><br>
            </fieldset>
        <?php endforeach; ?>

        <button type="submit">Submit Answers</button>
    </form>
</body>
</html>
