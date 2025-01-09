<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['grade'])) {
    $grade = $_GET['grade'];
    
    echo "You selected Grade " . htmlspecialchars($grade);
   
} else {
    echo "No grade selected.";
}
$display_name = $_SESSION['username'];


if (isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher' && isset($_SESSION['prefix'])) {
    $display_name = $_SESSION['prefix'] . ' ' . $display_name;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Lessons</title>
    <link rel="stylesheet" href="feature1.css">
   
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
    <h1 class="welcome-text">Welcome Back, <?php echo htmlspecialchars($display_name); ?>!</h1>
    <?php if (isset($_SESSION['role'])): ?>
        <h3 class="role-text">Role: <?php echo htmlspecialchars($_SESSION['role']); ?></h3>
    <?php endif; ?>
    <nav>
        <a href="home.php" id="home-link">Home</a>
        <a href="about.php" id="about-link">About</a>
        <a href="contact.php" id="contact-link">Contact</a>
    </nav>
        </header>
        <section class="lesson-section">
        <div class="lesson-card" onclick="showGradeModal('BASICS')">
        <img src="image/1.png" alt="Beginner Lessons Illustration">
        <h2>BASICS/BEGINNER LESSONS</h2>
        <p>Start with the basics, choose a language of your choice and begin your learning journey.</p>
    </div>
    <div class="lesson-card" onclick="showGradeModal('INTERMEDIATE')">
    <img src="image/2.png" alt="Intermediate Lessons Illustration">
    <h2>INTERMEDIATE LESSONS</h2>
    <p>Challenge yourself with lessons that are a little more challenging.</p>
    </div>
    <div class="lesson-card" onclick="showGradeModal('ADVANCED')">
    <img src="image/3.png" alt="Advanced Lessons Illustration">
    <h2>ADVANCED LESSONS</h2>
    <p>Advanced lessons to test your proficiency.</p>
    </div>

    <div id="gradeModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeGradeModal()">&times;</span>
        <h2 id="lesson-type">Select Your Grade Level</h2> 
        <button onclick="redirectToLesson(7)">Grade 7</button>
        <button onclick="redirectToLesson(8)">Grade 8</button>
        <button onclick="redirectToLesson(9)">Grade 9</button>
        <button onclick="redirectToLesson(10)">Grade 10</button>
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
                        document.getElementById('go-to-feature1').addEventListener('click', function() {
                window.location.href = 'feature1.php';
                
                closeGradeModal(); 
            });

            document.getElementById('go-to-feature2').addEventListener('click', function() {
                window.location.href = 'feature2.php';
               
                closeGradeModal(); 
            });

            document.getElementById('go-to-feature3').addEventListener('click', function() {
                window.location.href = 'feature3.php';
              
                closeGradeModal();
            });

            
            document.addEventListener("DOMContentLoaded", function() {
                closeGradeModal();
            });

            
            function showGradeModal(lessonType) {
                const modal = document.getElementById('gradeModal');
                modal.style.display = 'block';
            }

            function closeGradeModal() {
                const modal = document.getElementById('gradeModal');
                modal.style.display = 'none'; 
            }

           
            function redirectToLesson(grade) {
                window.location.href = `/lessons/${grade}`; 
            }

            document.addEventListener('DOMContentLoaded', function () {
   
    closeGradeModal();
});


function showGradeModal(lessonType) {
    const modal = document.getElementById('gradeModal');
    modal.style.display = 'block';

   
    modal.setAttribute('data-lesson-type', lessonType);
}


function closeGradeModal() {
    const modal = document.getElementById('gradeModal');
    modal.style.display = 'none';
}


function redirectToLesson(grade) {
    const modal = document.getElementById('gradeModal');
    const lessonType = modal.getAttribute('data-lesson-type');

    
    window.location.href = `/lessons/${lessonType}/${grade}`;
    closeGradeModal(); 
}
function showGradeModal(lessonType) {
    const modal = document.getElementById('gradeModal');
    const lessonText = document.getElementById('lesson-type');
    
 
    lessonText.textContent = lessonType.charAt(0).toUpperCase() + lessonType.slice(1) + " LESSONS";
    modal.style.display = 'block'; 

   
    modal.setAttribute('data-lesson-type', lessonType.toLowerCase());
}

function redirectToLesson(grade) {
    const modal = document.getElementById('gradeModal');
    const lessonType = modal.getAttribute('data-lesson-type'); 

   
    window.location.href = `/lingolearn/lessons/${lessonType.toLowerCase()}/grade${grade}.php`; 
    
    closeGradeModal(); 
}


    </script>
</body>
</html>