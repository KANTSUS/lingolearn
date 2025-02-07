<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$display_name = $_SESSION['username'];

// Check if the user is a teacher and has a prefix
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher' && isset($_SESSION['prefix'])) {
    $display_name = $_SESSION['prefix'] . ' ' . $display_name;
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
            <li><button id="go-to-feature1">Lesson</button></li>
            <li><button id="go-to-feature2">Pre-Test</button></li>
            <li><button id="go-to-feature3">AI Essay Feedback</button></li>
        </ul>
        <button id="logout-button" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <header>
        <h1 class="welcome-text">Welcome Back, <?php echo htmlspecialchars($display_name); ?>!</h1>
    <?php if (isset($_SESSION['role'])): ?>
        <h3 class="role-text">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></h3>
    <?php endif; ?>
    <nav>
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
