<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Teacher') {
    header("Location: login.php");
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected student's username
$student = isset($_GET['student']) ? $_GET['student'] : '';
if (empty($student)) {
    header("Location: view_students.php");
    exit();
}

// Fetch answers for the selected student
$sql = "SELECT q.question_text, a.answer 
        FROM answers a 
        JOIN questions q ON a.question_id = q.id 
        WHERE a.student_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($student); ?>'s Answers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Answers by <?php echo htmlspecialchars($student); ?></h1>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                    <td><?php echo htmlspecialchars($row['answer']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="view_students.php" class="back-button">Back to Students List</a>
</div>

</body>
</html>