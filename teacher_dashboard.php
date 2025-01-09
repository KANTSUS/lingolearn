<?php
session_start();
if ($_SESSION['role'] !== 'Teacher') {
    header("Location: home.php");
    exit();
}

$teacher_id = $_SESSION['username']; 


$conn = new mysqli('localhost', 'root', '', 'lingolearn');
$lessons_result = $conn->query("SELECT * FROM lessons WHERE teacher_id = '$teacher_id'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="teacher_dashboard.css">
</head>
<body>
    <h1>Welcome, Teacher <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2>Your Lessons</h2>
    <table>
        <thead>
            <tr>
                <th>Lesson Name</th>
                <th>View Students' Answers</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($lesson = $lessons_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lesson['lesson_name']); ?></td>
                    <td><a href="view_student_answers.php?lesson_id=<?php echo $lesson['lesson_id']; ?>">View Answers</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
