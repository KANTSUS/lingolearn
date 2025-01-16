<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['username'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to store answers
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $question_id => $answer) {
        if (strpos($question_id, 'question_') === 0) {
            $question_id = str_replace('question_', '', $question_id);
            $stmt = $conn->prepare("INSERT INTO answers (student_username, question_id, answer) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $_SESSION['username'], $question_id, $answer);
            $stmt->execute();
        }
    }
    $submitted = true;
}

// Fetch the lesson based on ID (can dynamically pass lesson ID)
$lesson_id = 1;  // Example lesson ID, replace this dynamically based on your session or URL
$sql = "SELECT * FROM questions WHERE lesson_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the lesson details
$lesson_sql = "SELECT * FROM lessons WHERE id = ?";
$lesson_stmt = $conn->prepare($lesson_sql);
$lesson_stmt->bind_param("i", $lesson_id);
$lesson_stmt->execute();
$lesson_result = $lesson_stmt->get_result();
$lesson = $lesson_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer the Questions</title>
    <link rel="stylesheet" href="view_question.css">
</head>
<body>

<div class="container">
    <h1>Answer the Questions</h1>

    <!-- PDF Viewer for the Lesson -->
    <h2>Lesson: <?php echo htmlspecialchars($lesson['lesson_name']); ?></h2>
    <object data="<?php echo htmlspecialchars($lesson['file_path']); ?>" type="application/pdf" width="600" height="400">
        <p>Your browser does not support PDFs. Download the PDF to view it: <a href="<?php echo htmlspecialchars($lesson['file_path']); ?>">Download PDF</a>.</p>
    </object>

    <?php if ($submitted): ?>
        <div class="popup" id="popup">
            <div class="popup-content">
                <h2>Submitted Successfully!</h2>
                <p>Your answers have been submitted.</p>
            </div>
            <button class="btn-close" onclick="closePopup()">Close</button>
        </div>
    <?php endif; ?>

    <form action="view_question.php" method="post" <?php echo $submitted ? 'style="display:none;"' : ''; ?>>
    <?php 
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { 
            $choices = explode(",", $row['choices']);
            ?>
            <div class="question-container">
                <p><strong>Question: </strong><?php echo htmlspecialchars($row['question_text']); ?></p>

                <?php if ($row['question_type'] == 'multiple_choice') {
                    
                    $letters = range('a', 'd'); 
                    foreach ($choices as $index => $choice) { 
                        $letter = $letters[$index]; 
                        ?>
                        <div class="multiple-choice">
                            <label>
                                <input type="radio" name="question_<?php echo $row['id']; ?>" value="<?php echo $letter; ?>">
                                <?php echo $letter . '. ' . htmlspecialchars(trim($choice)); ?>
                            </label>
                        </div>
                    <?php }
                } else { ?>
                    <label for="answer_<?php echo $row['id']; ?>">Your Answer:</label>
                    <input type="text" id="answer_<?php echo $row['id']; ?>" name="question_<?php echo $row['id']; ?>" placeholder="Enter your answer here">
                <?php } ?>
            </div>
        <?php 
        }
    } else {
        echo "<p>No questions found in the database.</p>";
    }
    ?>
    <button type="submit" class="btn">Submit Answers</button>
    </form>

    <a href="home.php" class="back-button">Back to Homepage</a>
</div>

<script>
    <?php if ($submitted): ?>
        window.onload = function() {
            document.getElementById('popup').style.display = 'block';
        };

        function closePopup() {
            window.location.href = 'home.php'; 
        }
    <?php endif; ?>
</script>

</body>
</html>
