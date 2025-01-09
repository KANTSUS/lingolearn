<?php
session_start();
if ($_SESSION['role'] !== 'Teacher') {
    header("Location: home.php");
    exit();
}

$lesson_id = $_GET['lesson_id'];

// Fetch student answers for the selected lesson
$conn = new mysqli('localhost', 'root', '', 'lingolearn');
$answers_result = $conn->query("SELECT * FROM student_answers WHERE lesson_id = '$lesson_id'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Answers for Lesson</title>
    <link rel="stylesheet" href="student_answers.css">
</head>
<body>
    <h1>Student Answers for Lesson <?php echo htmlspecialchars($lesson_id); ?></h1>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Answer</th>
                <th>Teacher's Answer</th>
                <th>Provide Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($answer = $answers_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($answer['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($answer['student_answer']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($answer['correct_answer']); ?>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="answer_id" value="<?php echo $answer['answer_id']; ?>">
                            <input type="text" name="correct_answer" placeholder="Provide answer" required>
                            <button type="submit" name="update_answer">Submit Answer</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    if (isset($_POST['update_answer'])) {
        $answer_id = $_POST['answer_id'];
        $correct_answer = $_POST['correct_answer'];

        // Update the correct answer provided by the teacher
        $conn->query("UPDATE student_answers SET correct_answer = '$correct_answer' WHERE answer_id = '$answer_id'");
        echo "<p>Answer updated successfully!</p>";
    }
    ?>
</body>
</html>
