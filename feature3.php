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
    <title>Homepage</title>
    <link rel="stylesheet" href="feature3.css">
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
    </div>
    <div class="quizzes-section">
    <h2>Quizzes</h2>
    <p>Take different types of quizzes to test your knowledge.</p>
    <div class="quiz-grid">
        <div class="quiz-card">
            <div class="quiz-icon verbs"></div>
            <h3>VERBS</h3>
            <p>Test your proficiency with verbs</p>
        </div>
        <div class="quiz-card">
            <div class="quiz-icon grammar"></div>
            <h3>GRAMMAR</h3>
            <p>Test how good your grammar is</p>
        </div>
        <div class="quiz-card">
            <div class="quiz-icon numbers"></div>
            <h3>NUMBERS AND COUNTING</h3>
            <p>Test yourself if you have memorized counting in various languages</p>
        </div>
        <div class="quiz-card">
            <div class="quiz-icon sentences"></div>
            <h3>SIMPLE SENTENCES</h3>
            <p>Test to see if you can compose sentences in various languages</p>
        </div>
    </div>
</div>

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

    function goToHome() {
        window.location.href = 'home.php';
    }
    </script>
</body>
</html>
s