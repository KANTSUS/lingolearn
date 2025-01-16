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

// Determine if the user is a teacher or student
$is_teacher = isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher';

// Retrieve grade if the user is a student
$grade = !$is_teacher && isset($_SESSION['grade']) ? $_SESSION['grade'] : null;
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
            <li><button id="go-to-feature1">Lesson</button></li>
            <li><button id="go-to-feature2">Reviewer</button></li>
            <li><button id="go-to-feature3">AI Essay Feedback</button></li>
        </ul>
        <button id="logout-button" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <header>
            <h1 class="welcome-text">Welcome Back, <?php echo htmlspecialchars($display_name); ?>!</h1>
            <?php if ($grade): ?>
                <h3 class="grade-text">Your Grade: <?php echo htmlspecialchars($grade); ?></h3>
            <?php else: ?>
                <!-- No grade displayed for teachers -->
                <?php if (!$is_teacher): ?>
                    <h3 class="grade-text">Your grade is not available.</h3>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['role'])): ?>
                <h3 class="role-text">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></h3>
            <?php endif; ?>
            <nav>
                <a href="home.php" id="home-link">Home</a>
                <a href="about.php" id="about-link">About</a>
                <a href="contact.php" id="contact-link">Contact</a>
            </nav>
        </header>
    </div>
    <div class="description">
        <section class="hero">
            <h2>Tool for English Learning</h2>
            <h3>Learn, speak, and connect with confidence.</h3>
            <p>Experience personalized learning with AI tools that enhance skills and provide feedback. Tailored lessons adapt to your style. Join us and start your journey to fluency today!</p>
        </section>

        <section>
            <h2>Upload and View Lessons</h2>
            <?php if ($is_teacher): ?>
                <p>As a teacher, you can upload lessons and quizzes here:</p>
                <button id="upload-lesson-button" onclick="goToUploadLesson()">Upload Lesson</button>
                <br><br>
                <p>View the list of students and their submitted answers:</p>
                <button id="view-student-answers-button" onclick="goToStudentAnswers()">View Student Answers</button>
            <?php else: ?>
                <p>As a student, you can view the lessons and questions uploaded by teachers.</p>
                <button id="view-questions-button" onclick="goToQuestions()">View Questions</button>
            <?php endif; ?>
        </section>
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

        function goToUploadLesson() {
            window.location.href = 'upload_lesson.php';
        }

        function goToQuestions() {
            window.location.href = 'view_questions.php';
        }

        function goToStudentAnswers() {
            window.location.href = 'view_student_answers.php';
        }
    </script>
    <script src="home.js"></script>
</body>
</html>
