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
    <link rel="stylesheet" href="feature2.css">
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
    <section class="customized-reviewer">
    <h1 class="main-title">Costumized Reviewer</h1>
    <h2 class="subtitle">Welcome to Your Personalized Learning Hub!</h2>
    <p class="description">
        Unlock your potential with our Customized Reviewer, offering tailored practice materials to enhance your learning journey.
    </p>
    <button class="create-design-button">+ Create Design</button>
    <div class="templates">
        <img src="image/4.png" alt="Reviewer Template" class="template-image">
        <img src="image/5.png" alt="Website Inspired Template" class="template-image">
        <img src="image/6.png" alt="Aesthetic Reviewers" class="template-image">
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

    function goToHome() {
        window.location.href = 'home.php';
    }
    </script>
</body>
</html>
