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
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="sidebar">
        <h2>LingoLearn</h2>
        <ul>
            <li><a href="#pronunciation-coach">Feature1</a></li>
            <li><a href="#speech-reader">Feature2</a></li>
            <li><a href="#interactive-lessons">Feature3</a></li>
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
    <div class="description">
        <section class="hero">
            <h2>Best Tools for Language Learning</h2>
            <h3>Learn, speak and connect with confidence.</h3>
            <p>Experience personalized learning with AI tools that enhance 
            skills and provide feedback. Tailored lessons adapt to your style.
            Join us and start your journey to fluency today!</p>
        </section>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
