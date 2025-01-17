<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Fetch all student scores
$query = "SELECT u.username, ss.score, ss.test_date FROM student_scores ss 
          JOIN users u ON ss.student_id = u.id ORDER BY ss.test_date DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Scores</title>
</head>
<body>
    <h1>Student Scores</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Score</th>
                <th>Test Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['score']; ?></td>
                    <td><?php echo $row['test_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
