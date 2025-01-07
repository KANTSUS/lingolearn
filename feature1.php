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
    // Close the modal on page load if it's already opened
    closeGradeModal();
});

// Function to show the grade selection modal
function showGradeModal(lessonType) {
    const modal = document.getElementById('gradeModal');
    modal.style.display = 'block';

    // Store the lessonType in the modal to use it later
    modal.setAttribute('data-lesson-type', lessonType);
}

// Function to close the grade selection modal
function closeGradeModal() {
    const modal = document.getElementById('gradeModal');
    modal.style.display = 'none';
}

// Redirect to the selected lesson page
function redirectToLesson(grade) {
    const modal = document.getElementById('gradeModal');
    const lessonType = modal.getAttribute('data-lesson-type');

    // Redirect using the selected lesson type and grade
    window.location.href = `/lessons/${lessonType}/${grade}`;
    closeGradeModal(); // Close the modal after selection
}
function showGradeModal(lessonType) {
    const modal = document.getElementById('gradeModal');
    const lessonText = document.getElementById('lesson-type');
    
    // Set the modal text based on the lesson type (BASICS, INTERMEDIATE, ADVANCED)
    lessonText.textContent = lessonType.charAt(0).toUpperCase() + lessonType.slice(1) + " LESSONS";
    modal.style.display = 'block'; // Show the modal

    // Store the lesson type in the modal so it can be used when redirecting
    modal.setAttribute('data-lesson-type', lessonType.toLowerCase());
}

function redirectToLesson(grade) {
    const modal = document.getElementById('gradeModal');
    const lessonType = modal.getAttribute('data-lesson-type'); // Get the selected lesson type (basics, intermediate, advanced)

    // Redirect to the corresponding lesson file based on lesson type and grade
    window.location.href = `/lingolearn/lessons/${lessonType.toLowerCase()}/grade${grade}.php`; 
    
    closeGradeModal(); // Close the modal after selection
}


    </script>
</body>
</html>