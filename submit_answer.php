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

// Get the lesson and questions for displaying
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : 0;
$lesson_query = $conn->prepare("SELECT * FROM lessons WHERE id = ?");
$lesson_query->bind_param("i", $lesson_id);
$lesson_query->execute();
$lesson_result = $lesson_query->get_result();
$lesson = $lesson_result->fetch_assoc();

if (!$lesson) {
    die("Lesson not found.");
}

// Get all questions related to the lesson
$question_query = $conn->prepare("SELECT * FROM questions WHERE lesson_id = ?");
$question_query->bind_param("i", $lesson_id);
$question_query->execute();
$questions_result = $question_query->get_result();

// Handle form submission for answers
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['answers'] as $question_id => $answer) {
        // Insert the answer into the answers table
        $stmt = $conn->prepare("INSERT INTO answers (student_username, question_id, answer) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $display_name, $question_id, $answer);
        if ($stmt->execute()) {
            // Successfully inserted the answer
        } else {
            echo "<div class='error'>Error: " . $conn->error . "</div>";
        }
    }

    // Redirect to a confirmation or thank you page
    header("Location: thank_you.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Answers for Lesson</title>
    <link rel="stylesheet" href="submit_answers.css">
</head>
<body>
    <div class="container">
        <h1>Submit Answers for: <?php echo htmlspecialchars($lesson['lesson_name']); ?></h1>
        
        <form action="" method="post">
            <?php while ($question = $questions_result->fetch_assoc()): ?>
                <div class="question">
                    <h3><?php echo htmlspecialchars($question['question_text']); ?></h3>

                    <?php if ($question['question_type'] == 'fill_in_the_blank'): ?>
                        <input type="text" name="answers[<?php echo $question['id']; ?>]" placeholder="Your answer">
                    <?php elseif ($question['question_type'] == 'multiple_choice'): ?>
                        <?php
                        $choices = explode(", ", $question['choices']);
                        foreach ($choices as $index => $choice) {
                            echo "<label><input type='radio' name='answers[{$question['id']}]' value='" . chr(65 + $index) . "'> " . htmlspecialchars($choice) . "</label><br>";
                        }
                        ?>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="btn">Submit Answers</button>
        </form>
    </div>

    <a href="home.php" class="back-button">Back to Homepage</a>
</body>
</html>
