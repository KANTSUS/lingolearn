<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Lessons</title>
    <link rel="stylesheet" href="feature1.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>LingoLearn</h2>
        <ul>
            <li><button id="go-to-feature1">Feature1</button></li>
            <li><button id="go-to-feature2">Feature2</button></li>
            <li><button id="go-to-feature3">Feature3</button></li>
        </ul>
        <button id="logout-button" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <header>
            <h1 class="welcome-text">Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <nav>
                <a href="home.php" id="home-link">Home</a>
                <a href="about.php" id="about-link">About</a>
                <a href="contact.php" id="contact-link">Contact</a>
            </nav>
        </header>

                <section class="lesson-section">
            <h1 class="lesson-title">Interactive Lessons</h1>
            <div class="lesson-card">
                <img src="image/1.png" alt="Beginner Lessons Illustration">
                <h2>BASICS/BEGINNER LESSONS</h2>
                <p>Start with the basics, choose a language of your choice and begin your learning journey.</p>
            </div>
            <div class="lesson-card">
                <img src="image/2.png" alt="Intermediate Lessons Illustration">
                <h2>INTERMEDIATE LESSONS</h2>
                <p>Challenge yourself with lessons that are a little more challenging.</p>
            </div>
            <div class="lesson-card">
                <img src="image/3.png" alt="Advanced Lessons Illustration">
                <h2>ADVANCED LESSONS</h2>
                <p>Advanced lessons to test your proficiency.</p>
            </div>
        </section>

    

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        document.getElementById('go-to-feature1').addEventListener('click', function() {
            window.location.href = 'feature1.php';
        });

        document.getElementById('go-to-feature2').addEventListener('click', function() {
            window.location.href = 'feature2.php';
        });

        document.getElementById('go-to-feature3').addEventListener('click', function() {
            window.location.href = 'feature3.php';
        });
    </script>
</body>
</html>