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
            <li><button id="go-to-feature3">AI Essay Feedback</button></li>
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
                <div class="lesson-card" onclick="showGradeModal('beginner')">
                    <img src="image/1.png" alt="Beginner Lessons Illustration">
                    <h2>BASICS/BEGINNER LESSONS</h2>
                    <p>Start with the basics, choose a language of your choice and begin your learning journey.</p>
                </div>
                <div class="lesson-card" onclick="showGradeModal('intermediate')">
                    <img src="image/2.png" alt="Intermediate Lessons Illustration">
                    <h2>INTERMEDIATE LESSONS</h2>
                    <p>Challenge yourself with lessons that are a little more challenging.</p>
                </div>
                <div class="lesson-card" onclick="showGradeModal('advanced')">
                    <img src="image/3.png" alt="Advanced Lessons Illustration">
                    <h2>ADVANCED LESSONS</h2>
                    <p>Advanced lessons to test your proficiency.</p>
                </div>

                <!-- Modal for Grade Level Selection -->
                <div id="gradeModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeGradeModal()">&times;</span>
                        <h2>Select Your Grade Level</h2>
                        <button onclick="redirectToLesson('grade1')">Grade 1</button>
                        <button onclick="redirectToLesson('grade2')">Grade 2</button>
                        <button onclick="redirectToLesson('grade3')">Grade 3</button>
                    </div>
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

        function showGradeModal(lessonType) {
            const modal = document.getElementById('gradeModal');
            modal.style.display = 'block'; // Show the modal

            // Store the lesson type (beginner, intermediate, advanced) for redirection
            modal.dataset.lessonType = lessonType;
        }

        function closeGradeModal() {
            const modal = document.getElementById('gradeModal');
            modal.style.display = 'none'; // Hide the modal
        }

        function redirectToLesson(gradeLevel) {
            const modal = document.getElementById('gradeModal');
            const lessonType = modal.dataset.lessonType;

            // Construct the URL for redirection
            const url = `lessons/${lessonType}_${gradeLevel}.php`;
            window.location.href = url;
        }
    </script>
</body>
</html>