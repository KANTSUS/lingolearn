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

// Check if the lessons table has the 'lesson_name' column
$result = $conn->query("DESCRIBE lessons");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

if (!in_array('lesson_name', $columns)) {
    // Add 'lesson_name' column if it doesn't exist
    $alterTableQuery = "ALTER TABLE lessons ADD COLUMN lesson_name VARCHAR(255)";
    if ($conn->query($alterTableQuery) === FALSE) {
        die("Error adding column 'lesson_name': " . $conn->error);
    }
}

// Check if form is submitted for lesson and question addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['lessonFile']) && isset($_POST['questionText']) && isset($_POST['questionType'])) {
    $lessonFile = $_FILES['lessonFile'];
    $questions = $_POST['questionText'];
    $questionType = $_POST['questionType'];
    
    // Check if file is a valid PDF
    if ($lessonFile['type'] == 'application/pdf') {
        // Move the uploaded file to the "uploads" directory
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($lessonFile["name"]);
        
        if (move_uploaded_file($lessonFile["tmp_name"], $target_file)) {
            // Insert the lesson information into the lessons table
            $lesson_name = basename($lessonFile["name"]);
            $stmt = $conn->prepare("INSERT INTO lessons (lesson_name, file_path) VALUES (?, ?)");
            if ($stmt === false) {
                echo "<div class='error'>Error preparing statement: " . $conn->error . "</div>";
            } else {
                $stmt->bind_param("ss", $lesson_name, $target_file);
                if ($stmt->execute()) {
                    $lesson_id = $conn->insert_id;  // Get the inserted lesson's ID
                    
                    // Flag to show the success popup
                    $questionAdded = false;

                    // Handle questions
                    foreach ($questions as $index => $questionText) {
                        // Ensure we have a valid question type
                        $question_type = isset($questionType[$index]) ? $questionType[$index] : '';
                        if (empty($question_type)) {
                            continue;  // Skip if question type is not provided
                        }

                        $choicesList = '';
                        $correctAnswer = '';

                        // Handle multiple-choice questions
                        if ($question_type == "multiple_choice") {
                            if (isset($_POST['choices'][$index])) {
                                $choicesList = implode(", ", $_POST['choices'][$index]);
                            }

                            if (isset($_POST['correctAnswer'][$index])) {
                                $correctAnswer = $_POST['correctAnswer'][$index];
                            }

                            // Insert question into the questions table
                            $stmt = $conn->prepare("INSERT INTO questions (lesson_id, question_text, question_type, choices, correct_answer) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("issss", $lesson_id, $questionText, $question_type, $choicesList, $correctAnswer);
                        } elseif ($question_type == "fill_in_the_blank") {
                            // Insert fill-in-the-blank question into the questions table
                            $stmt = $conn->prepare("INSERT INTO questions (lesson_id, question_text, question_type) VALUES (?, ?, ?)");
                            $stmt->bind_param("iss", $lesson_id, $questionText, $question_type);
                        }

                        // Execute the statement
                        if ($stmt->execute()) {
                            $questionAdded = true;
                        } else {
                            echo "<div class='error'>Error: " . $conn->error . "</div>";
                        }
                    }

                    // Close the statement
                    if (isset($stmt)) {
                        $stmt->close();
                    }
                } else {
                    echo "<div class='error'>Error executing statement: " . $conn->error . "</div>";
                }
            }
        } else {
            echo "<div class='error'>Sorry, there was an error uploading your file.</div>";
        }
    } else {
        echo "<div class='error'>Only PDF files are allowed for the lesson file.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Lesson and Questions</title>
    <link rel="stylesheet" href="upload_lesson.css">
</head>
<body>
    <div class="container">
        <h1>Upload Lesson and Questions</h1>

        <h2>Upload a Lesson (PDF File) and Add Questions</h2>

        <!-- Upload Lesson -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="lesson-upload">
                <label for="lessonFile">Upload Lesson (PDF):</label>
                <input type="file" name="lessonFile" id="lessonFile" accept="application/pdf" required>
            </div>

            <div id="questionsSection">
                <!-- Dynamically added questions will appear here -->
                <div class="question-container">
                    <label for="questionType">Question Type:</label>
                    <select name="questionType[]" class="questionType" onchange="showFields(this)" required>
                        <option value="">Choose...</option>
                        <option value="fill_in_the_blank">Fill in the Blank</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>

                    <div class="fill-in-the-blank" style="display: none;">
                        <label for="questionText">Question:</label>
                        <input type="text" name="questionText[]">
                    </div>

                    <div class="multiple-choice" style="display: none;">
                        <label for="questionText">Question:</label>
                        <input type="text" name="questionText[]">

                        <label for="choices">Choices:</label>
                        <input type="text" name="choices[][0]" placeholder="Choice A">
                        <input type="text" name="choices[][1]" placeholder="Choice B">
                        <input type="text" name="choices[][2]" placeholder="Choice C">
                        <input type="text" name="choices[][3]" placeholder="Choice D">

                        <label for="correctAnswer">Correct Answer:</label>
                        <input type="text" name="correctAnswer[]" placeholder="Enter correct answer (a, b, c, d)">
                    </div>
                </div>
            </div>
            <button type="button" class="btn" onclick="addQuestion()">Add Another Question</button>
            <button type="submit" class="btn">Submit Lesson and Questions</button>
        </form>
    </div>

    <a href="home.php" class="back-button">Back to Homepage</a>

    <!-- Popup message -->
    <?php if (isset($questionAdded) && $questionAdded): ?>
        <div class="popup" id="popup">
            <div class="popup-content">
                <h2>Lesson and Questions Added Successfully!</h2>
                <p>Your lesson and questions have been successfully added.</p>
            </div>
            <button class="btn-close" onclick="closePopup()">Go to Homepage</button>
        </div>
    <?php endif; ?>

    <script>
        function showFields(select) {
            const container = select.closest('.question-container');
            const fillInTheBlank = container.querySelector('.fill-in-the-blank');
            const multipleChoice = container.querySelector('.multiple-choice');

            fillInTheBlank.style.display = select.value === 'fill_in_the_blank' ? 'block' : 'none';
            multipleChoice.style.display = select.value === 'multiple_choice' ? 'block' : 'none';
        }

        function addQuestion() {
            const questionSection = document.getElementById("questionsSection");
            const newQuestion = document.createElement("div");
            newQuestion.classList.add("question-container");
            newQuestion.innerHTML = questionSection.innerHTML;
            questionSection.appendChild(newQuestion);
        }

        <?php if (isset($questionAdded) && $questionAdded): ?>
            window.onload = function() {
                document.getElementById('popup').style.display = 'block';
            };

            function closePopup() {
                window.location.href = 'home.php'; // Redirect to homepage
            }
        <?php endif; ?>
    </script>
</body>
</html>
