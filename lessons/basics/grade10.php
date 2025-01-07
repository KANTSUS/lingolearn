<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade 7 - Basic Lessons</title>
    <link rel="stylesheet" href="/lingolearn/lessons/grade7.css">
</head>
<body>
    <div class="sidebar">
        <h2>LingoLearn</h2>
        <ul>
            <li><button id="go-to-feature1">Feature1</button></li>
            <li><button id="go-to-feature2">Feature2</button></li>
            <li><button id="go-to-feature3">AI Essay Feedback</button></li>
        </ul>
        <button id="logout-button" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <header>
            <h1>Grade 10 - Beginner Lessons</h1>
        </header>
        <section class="lesson-content">
            <p>Welcome to Grade 10 Basic Lessons. Let's start learning!</p>

            <!-- Links to other grades in Beginner Lessons -->
            <h3>Other Grades</h3>
            <ul>
                <li><a href="/lingolearn/lessons/basics/grade7.php">Grade 7</a></li>
                <li><a href="/lingolearn/lessons/basics/grade8.php">Grade 8</a></li>
                <li><a href="/lingolearn/lessons/basics/grade9.php">Grade 9</a></li>
            </ul>

            <!-- Links to Intermediate and Advanced Lessons -->
            <h3>Other Lesson Types</h3>
            <ul>
                <li><a href="/lingolearn/lessons/intermediate/grade10.php">Intermediate Grade 10</a></li>
                <li><a href="/lingolearn/lessons/advanced/grade10.php">Advanced Grade 10</a></li>
            </ul>

            <button onclick="goBackToFeature1()">Go Back to Feature 1</button>
        </section>
    </div>

    <script>
        function logout() {
            window.location.href = '../../logout.php';
        }

        function goBackToFeature1() {
            window.location.href = '../../feature1.php'; // Redirect back to Feature 1
        }
    </script>
</body>
</html>