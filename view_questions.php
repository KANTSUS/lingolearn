<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$is_teacher = isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher';

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch questions for students or student answers for teachers
if ($is_teacher) {
    // Fetch student answers for teachers
    $sql = "SELECT sa.id, sa.student_answer, sa.student_username, sa.created_at, q.question_text 
            FROM student_answers sa 
            JOIN questions q ON sa.question_id = q.id
            ORDER BY sa.created_at DESC";
    $result = $conn->query($sql);
} else {
    // Fetch questions for students
    $sql = "SELECT * FROM questions ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View <?php echo $is_teacher ? 'Student Answers' : 'Questions'; ?></title>
    <link rel="stylesheet" href="view_question.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $is_teacher ? 'Student Answers' : 'Questions'; ?></h1>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <?php if ($is_teacher): ?>
                            <th>Question</th>
                            <th>Student Answer</th>
                            <th>Student Username</th>
                            <th>Submission Date</th>
                        <?php else: ?>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Grade</th>
                            <th>Lesson Type</th>
                            <th>Date Created</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <?php if ($is_teacher): ?>
                                <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_answer']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_username']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <?php else: ?>
                                <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                                <td><?php echo htmlspecialchars($row['question_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['grade']); ?></td>
                                <td><?php echo htmlspecialchars($row['lesson_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
    </div>

    <a href="home.php" class="back-button">Back to Homepage</a>

</body>
</html>

<?php
$conn->close();
?>
