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

// Correcting the query to not use "lesson_id" if it's not part of the schema
$sql = "SELECT a.student_username, q.question_text, a.answer 
        FROM answers a 
        JOIN questions q ON a.question_id = q.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Answers</title>
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

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Student Answers</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Student Username</th>
                    <th>Question</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_username']); ?></td>
                        <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                        <td><?php echo htmlspecialchars($row['answer']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="home.php" class="back-button">Back to Homepage</a>
</div>

</body>
</html>
